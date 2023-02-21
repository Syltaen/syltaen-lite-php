<?php

namespace Syltaen;

include __DIR__ . "/app/functions.php";

use Pecee\SimpleRouter\SimpleRouter as Router;
use Pecee\Http\Request;

Router::setDefaultNamespace("\Syltaen");

// ==================================================
// > ROUTES
// ==================================================
Router::partialGroup(config("base_uri"), function () {

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
        return (new PageController)->error404();
    }

});

Router::start();