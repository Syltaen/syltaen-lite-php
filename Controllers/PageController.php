<?php

namespace Syltaen;

class PageController extends BaseController
{

    // ==================================================
    // > CONTENT PAGES
    // ==================================================
    /**
     * Home page : Introduction
     *
     * @return string
     */
    public function home()
    {
        return $this->view("pages/home");
    }



    // ==================================================
    // > SPECIAL PAGES
    // ==================================================
    /**
     * Error 404
     *
     * @param string $version
     * @return string
     */
    public function error404()
    {
        $this->render("pages/error404");
        exit;
    }
}