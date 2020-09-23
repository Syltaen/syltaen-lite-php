<?php

// ==================================================
// > COMMON CONFIG
// ==================================================

/**
 * Site name
 */
define("SITE_NAME", "Website name");


/**
 * Base DIR
 */
define("BASE_DIR", __DIR__ . "/../..");


/**
 * Base URI
 */
define("BASE_URI", $_SERVER["BASE_PATH"]);


// ==================================================
// > CONFIG LOADING
// ==================================================
if (!empty($_SERVER["HTTP_HOST"]) && $_SERVER["HTTP_HOST"] == "localhost") {
    include __DIR__ . "/config-local.php";
} else {
    include __DIR__ . "/config-prod.php";
}