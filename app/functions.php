<?php

namespace Syltaen;

require __DIR__ . "/config/config.php";
require __DIR__ . "/tools.php";


// ==================================================
// > Autoloading & vendors
// ==================================================
require __DIR__ . "/Helpers/Files.php";
spl_autoload_register("Syltaen\Files::autoload");
Files::import("app/vendors/vendor/autoload.php");


// ==================================================
// > TIMEZONE CONFIG
// ==================================================
date_default_timezone_set("Europe/Brussels");


// ==================================================
// > Custom error-handler
// ==================================================
if (DEBUG || isset($_GET["debug"])) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    ($handler = (new \Whoops\Handler\PrettyPageHandler))->setEditor("vscode");
    (new \Whoops\Run)->pushHandler($handler)->register();
}