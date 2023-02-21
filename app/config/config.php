<?php

return array_merge([

    // =============================================================================
    // > COMMON
    // =============================================================================

    /**
     * Site name
     */
    "site_name" => "Website name",

    /**
     * Base DIR
     */
    "base_dir" => __DIR__ . "/../..",

    /**
     * Base URI
     */
    "base_uri" => $_SERVER["BASE_PATH"],

    /**
     * Debug log
     */
    "log"    => [

        // Number of backtrace calls to include in debugs
        "backtrace_level" => 1,

        // Wether to fetch all fields of model logged or not.
        // Can cause infinite loop in some cases.
        "fetch_fields"    => true,

        // Number of lines to keep in each logfile
        "log_history"     => 50000,
    ],
], [


    // =============================================================================
    // > DEVELOPPMENT
    // =============================================================================
    "dev" => [
        /**
         * Activate the display of errors
         */
        "debug" => true,

        /**
         * HTTP CACHE
         */
        "http_cache" => false,

        /**
         * Database
         */
        "db" => [
            "name" => "syltaen-php",
            "host" => "localhost",
            "user" => "root",
            "pass" => "root"
        ]
    ],

    // =============================================================================
    // > PRODUCTION
    // =============================================================================
    "prod" => [
        /**
         * Activate the display of errors
         */
        "debug" => isset($_GET["debug"]),

        /**
         * HTTP CACHE
         */
        "http_cache" => true,

        /**
         * Database
         */
        "db" => [
            "name" => "",
            "host" => "",
            "user" => "",
            "pass" => ""
        ]
    ]
][!empty($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == "localhost" ? "dev" : "prod"]);