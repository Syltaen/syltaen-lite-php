<?php

namespace Syltaen;

abstract class BaseController extends Controller
{

    public $view = "page";

    /**
     * Add data for the rendering
     */
    public function __construct($args = [])
    {
        parent::__construct($args);

        // Add common data needed all pages
        $this->setBase();
    }


    // ==================================================
    // > PARTS
    // ==================================================
    /**
     * Rendering of the website main header
     *
     * @return array
     */
    protected function header()
    {

    }


    /**
     * Data for the website main footer
     *
     * @return array
     */
    protected function footer()
    {

    }


    /**
     * Rendering of all the websites menus
     *
     * @return array
     */
    protected function menus()
    {

    }

    // ==================================================
    // > SETTERS / ADDERS
    // ==================================================
    /**
     * Add common data needed each page
     * Can be launched after modifing the global $post to refresh data
     * @return void
     */
    protected function setBase()
    {
        $this->addData([
            "site"       => [
                "header"       => $this->header(),
                "footer"       => $this->footer(),
                "menus"        => $this->menus(),

                "page_title"   => config("site_name"),

                "url"          => config("base_uri"),
                "name"         => config("site_name"),
                "language"     => "fr-FR",
                "charset"      => "UTF-8",
                "body_class"   => [],
            ],
        ]);
    }

    /**
     * Change the document title (require YOAST SEO)
     *
     * @param string $title
     * @return void
     */
    protected function setPageTitle($title, $raw = false)
    {
        $this->data["site"]["page_title"] = $raw ? $title : config("site_name") . " - " . $title;
    }


    /**
     * Add class to the body
     *
     * @param array|string $classes Class(es) to add
     * @return void
     */
    public function addBodyClass($classes)
    {
        $this->data["site"]["body_class"] = array_merge(
            $this->data["site"]["body_class"],
            (array) $classes
        );
    }
}