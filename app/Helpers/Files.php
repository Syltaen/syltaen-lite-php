<?php

namespace Syltaen;

abstract class Files
{
    // ==================================================
    // > PATHS & LOADING
    // ==================================================
    /**
     * Load one or several files by providing a folder shortcut and a list of filenames
     *
     * @param string $folder
     * @param array|string $files
     * @return void
     */
    public static function import($folders = [""], $files = [""])
    {
        $folders = (array) $folders;
        $files   = (array) $files;
        $list    = [];

        // Create an import list
        foreach ($folders as $folder) {
            foreach ($files as $file) {
                $list[] = trim($folder ."/" . $file, "/");
            }
        }

        foreach ($list as $item) {
            // Is a file
            if (strpos($item, ".")) {
                require_once(self::path($item));
                continue;
            }

            // Is a folder
            foreach (self::in($item, ".php") as $file) {
                require_once(self::path($item . "/" . $file));
            }
        }
    }



    /**
     * File path resolution
     *
     * @param string $key
     * @param string $filename
     * @return string
     */
    public static function path($path_from_root = "")
    {
        return str_replace("\\", "/", BASE_DIR . "/" . $path_from_root);
    }


    /**
     * Remove file or directory recusivelry
     *
     * @return void
     */
    public static function remove($path, $absolute_path = false)
    {
        $path = $absolute_path ? $path : Files::path($path);

        // Use wildcard -> remove with glob
        if (strpos($path, "*") !== false) {
            $files = glob($path);
            foreach ($files as $file) Files::remove($file, true);
            return;
        }

        // Is a file, remove it
        if (!is_dir($path)) {
            return unlink($path);
        }

        // Is a dir, remove all file in it recursively
        $dir = substr($path, -1) != "/" ? $path . "/" : $path;
        $openDir = opendir($dir);
        while ($file = readdir($openDir)) {
            if (in_array($file, [".", ".."])) continue;
            Files::remove($dir . $file, true);
        }
        closedir($openDir);

        // Then remove the dir
        rmdir($dir);
    }

    /**
     * File url resolution
     *
     * @param string $key
     * @param string $filename
     * @return string
     */
    public static function url($path_from_root = "")
    {
        return BASE_URI . "/" . $path_from_root;
    }

    /**
     * Return the time the file was last modified
     *
     * @param string $key
     * @param string $file
     * @return int : number of ms
     */
    public static function time($file)
    {
        return filemtime(self::path($file));
    }



    /**
     * Autoloader matching PHP-FIG PSR-4 and PSR-0 standarts
     *
     * @param string $classname
     * @return void
     */
    public static function autoload($classname)
    {
        // Not from this namespace
        if (strncmp("Syltaen", $classname, 7) !== 0) return;

        // Remove the namespace "Syltaen"
        $classname = substr($classname, 8);

        // Find the file in one of the classes folders
        if ($found = self::findIn("{$classname}.php", [
            "app/lib",
            "app/Helpers",
            "Controllers",
            "Controllers/processors",
            "Models",
            "app/Forms"
        ])) {
            require_once $found;
        }
    }


    /**
     * Find a file in one off the provided folders
     *
     * @param string $file The name of the file
     * @param array $folders A list of folder's paths (from the theme root)
     * @param bool $returnAll Return all matches instead of only the first one
     * @return string The file path
     */
    public static function findIn($file, $folders, $depth = 2, $returnAll = false)
    {
        // Create the folder pattern
        $folders = array_map(function ($folder) {
            return self::path($folder);
        }, $folders);

        // Create the depth pattern
        for ($depth_pattern_folder = $depth_pattern = ""; $depth > 0; $depth--) {
            $depth_pattern_folder .= "*/";
            $depth_pattern        .= ",$depth_pattern_folder";
        }

        $results = [];

        // Search in each folder, one by one and merge to $result
        foreach ($folders as $folder) {
            $results = array_merge($results, glob($folder . "{" . $depth_pattern . "}" . $file, GLOB_BRACE));
        }

        if (empty($results)) return false;

        return $returnAll ? $results : $results[0];
    }

    /**
     * Return a list of files found in a specific folder
     *
     * @param string $folder
     * @return array
     */
    public static function in($folder, $match = false, $show_hidden = false)
    {
        $files  = [];

        // Nothing
        if (!is_dir(self::path($folder))) return [];

        foreach (scandir(self::path($folder)) as $file) {

            // Does not list hidden files or navigation
            if (!$show_hidden && ($file[0] == "." || $file[0] == "_")) continue;

            // Has to match
            if ($match && strpos($file, $match) === false) continue;

            $files[] = $file;
        }

        return $files;
    }


    /**
     * Create an array of files item, separating multi-files into separate entries
     *
     * @param array $files Array of files
     * @return array
     */
    public static function flattenFilesArray($files)
    {
        $unclean = (array) $files;
        $files   = [];

        // Transform multiple-files array into separates files
        foreach ($unclean as $name=>$file) {

            // Not a multipe-files array
            if (!is_array($file["name"])) {
                $files[$name] = $file;
                continue;
            }

            // Multiple-files array, flatten it
            foreach ($file["name"] as $index=>$n) {
                $newFile = [];
                foreach ($file as $attr=>$value) $newFile[$attr] = $value[$index];
                $files[$name."_".$index] = $newFile;
            }
        }

        return $files;
    }
}