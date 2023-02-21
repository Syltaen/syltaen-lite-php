<?php

namespace Syltaen;

// ==================================================
// > Autoloading & vendors
// ==================================================
require __DIR__ . "/Helpers/Cache.php";
require __DIR__ . "/Helpers/Data.php";
require __DIR__ . "/Helpers/Set.php";
require __DIR__ . "/tools.php";
require __DIR__ . "/config/config.php";
require __DIR__ . "/Helpers/Files.php";
spl_autoload_register("Syltaen\Files::autoload");
Files::import("app/vendors/vendor/autoload.php");

// ==================================================
// > Custom error-handler
// ==================================================
if (config("debug")) {
    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    ($handler = (new \Whoops\Handler\PrettyPageHandler))->setEditor("vscode");
    (new \Whoops\Run)->pushHandler($handler)->register();
}