<?php

namespace Syltaen;

abstract class Data
{
    // ==================================================
    // > SESSIONS
    // ==================================================
    /**
     * Get a value from the session or store one
     *
     * @param  array|string $data If array, store. If string, read.
     * @return void
     */
    public static function session($data = null, $session_key = "syltaen")
    {
        if (!session_id()) {
            session_start();
        }

        // write
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $_SESSION[$session_key][$key] = $value;
            }
            return true;
        }

        // read one
        if (is_string($data)) {
            if (isset($_SESSION[$session_key][$data])) {
                return $_SESSION[$session_key][$data];
            }
            return null;
        }

        // read all
        if ($session_key && isset($_SESSION[$session_key])) {
            return $_SESSION[$session_key];
        }

        return $_SESSION;
    }

    // ==================================================
    // > FLASH MESSAGES
    // ==================================================
    /**
     * Get or store a value in the current page session
     *
     * @param  [type] $data
     * @return void
     */
    public static function currentPage($data = null)
    {
        return static::session($data, "syltaen_current_page");
    }

    /**
     * Get or store a value in the next page session
     *
     * @param  [type] $data
     * @return void
     */
    public static function nextPage($data = null, $redirection = false, $ttl = 1)
    {
        static::addFlashMessage($data, $ttl);

        if ($redirection) {
            header("Location: $redirection");
            exit;
        }
    }

    /**
     * Go to the next session page, clearing flash data
     *
     * @return void
     */
    public static function goToNextSessionPage()
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["syltaen_current_page"] = [];

        // Remove one to all TTL
        $messages = static::getFlashMessages();

        foreach ($messages as &$message) {
            $message["ttl"]--;

            if ($message["ttl"] === 0) {
                $_SESSION["syltaen_current_page"] = $message["messages"];
            }
        }unset($message);

        // Remove expired messages
        $messages = array_filter($messages, function ($message) {
            return $message["ttl"] > 0;
        });

        $_SESSION["syltaen_messages"] = $messages;
    }

    /**
     * @param $messages
     * @param $ttl
     */
    public static function addFlashMessage($messages, $ttl = 1)
    {
        if (!session_id()) {
            session_start();
        }

        $_SESSION["syltaen_messages"] = static::getFlashMessages();

        $_SESSION["syltaen_messages"][] = [
            "messages" => $messages,
            "ttl"      => $ttl,
        ];
    }

    /**
     * @return mixed
     */
    public static function getFlashMessages()
    {
        if (empty($_SESSION["syltaen_messages"])) {
            return [];
        }

        return $_SESSION["syltaen_messages"];
    }

    // ==================================================
    // > GLOBAL DATA
    // ==================================================
    /**
     * Set globals data shared by the whole application
     *
     * @param  array|string $data
     * @param  bool         $merge
     * @return void
     */
    public static function globals($data = null, $merge = false)
    {
        global $syltaen_global_data;

        // write
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $old_value                 = isset($syltaen_global_data[$key]) ? $syltaen_global_data[$key] : [];
                    $syltaen_global_data[$key] = array_merge_recursive($old_value, $value);
                } else {
                    $syltaen_global_data[$key] = $value;
                }
            }
            return true;
        }

        // read one
        if (is_string($data)) {
            if (isset($syltaen_global_data[$data])) {
                return $syltaen_global_data[$data];
            }
            return null;
        }

        return $syltaen_global_data;
    }
}