<?php

namespace Syltaen;

class View
{
    const CACHE = true;

    // ==================================================
    // > PUBLIC
    // ==================================================

    /**
     * Render the provided data
     *
     * @param string $filename
     * @param array $data
     * @param boolean $echo
     * @return string|null
     */
    public static function render($filename, $context = false)
    {
        return static::getRenderer()->renderFile(
            static::path($filename),
            static::prepareContext($context)
        );
    }


    /**
     * Display a view
     *
     * @return void
     */
    public static function display($filename, $context = false)
    {
        return static::getRenderer()->displayFile(
            static::path($filename),
            static::prepareContext($context)
        );
    }


    // ==================================================
    // > PRIVATE
    // ==================================================
    /**
     * Singleton renderer
     *
     * @var \Pug\Pug
     */
    private static $renderer = null;


    /**
     * Get the singleton renderer
     *
     * @return void
     */
    private static function getRenderer()
    {
        if (is_null(static::$renderer)) {
            static::$renderer = new \Pug\Pug([
                "extension"          => ".pug",
                // "expressionLanguage" => "php",

                // Caching
                "cache"         => static::CACHE ? Files::path("app/cache/pug-php/") : false,
                "upToDateCheck" => config("debug"), // Alaws serve cached versions in production

                // Options
                "strict" => true,
            ]);

            static::setOptions();
        }

        return static::$renderer;
    }


    /**
     * Get the full path of a view file
     *
     * @param string $filename
     * @return string
     */
    private static function path($filename)
    {
        $filepath = Files::path("views/" . $filename . ".pug");
        if (!file_exists($filepath)) die("View file not found : $filepath");
        return $filepath;
    }


    /**
     * Get the full path of a view file
     *
     * @param array|bool $context
     * @return string
     */
    private static function prepareContext($context = false)
    {
        if (!$context) return [];

        // Add helper functions
        $context = array_merge(
            $context,
            static::helpers()
        );

        return $context;
    }



    /**
     * Set options for the rendererd
     *
     * @return void
     */
    private static function setOptions()
    {
        static::$renderer = static::getRenderer();


        static::$renderer;
    }


    /**
     * Add helpers function to the context
     *
     * @return void
     */
    private static function helpers()
    {
        return [

            // Return local url
            "_url" => function ($path) {
                return config("base_uri") . $path;
            },

            // Return an image url
            "_img" => function ($image) {
                return Files::url("build/img/" . $image);
            },

            // CSS file url with cache busting
            "_css" => function ($file) {
                return Files::url("build/css/{$file}") . "?version=" . Files::time("build/css/{$file}");
            },

            // JS file url with cache busting
            "_js" => function ($file) {
                return Files::url("build/js/{$file}") . "?version=" . Files::time("build/js/{$file}");
            },

            // Prefix a phone number url
            "_tel" => function ($tel) {
                return "tel:" . preg_replace("/[^0-9]/", "", $tel);
            },

            // Prefix a mail url
            "_mailto" => function ($mail) {
                return "mailto:" . $mail;
            }
        ];
    }
};