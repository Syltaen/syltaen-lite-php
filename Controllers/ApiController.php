<?php

namespace Syltaen;

class ApiController extends Controller
{

    public function __invoke($method)
    {
        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        wp_send_json([
            "error" => "Api method not found or not callable."
        ]);
    }

    // ==================================================
    // > API routes
    // ==================================================
    /**
     * Playground to test things
     *
     * @param string $target
     * @return void
     */
    private function lab($target = false)
    {

    }


    /**
     * Output the result of phpinfo()
     *
     * @return void
     */
    private function phpinfo()
    {
        phpinfo();
    }
}