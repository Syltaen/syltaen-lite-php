<?php

include __DIR__ . "/app/functions.php";

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;

Router::setDefaultNamespace("\Syltaen");

// ==================================================
// > ROUTES
// ==================================================
Router::partialGroup(BASE_URI, function () {

    // API
    Router::get("api/{method}", "ApiController");

    // Homepage
    Router::get("/", "PageController@home");

});


// ==================================================
// > 404
// ==================================================
Router::error(function(Request $request, \Exception $exception) {

    if ($exception->getCode() == 404) {
        return (new Syltaen\PageController)->error404();
    }

});

Router::start();