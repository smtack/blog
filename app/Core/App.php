<?php

namespace Core;

use Controllers\Pages;

class App
{
    protected $controller = Pages::class;
    protected $method = 'index';
    protected $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();

        if (isset($url[0])) {
            $controller = "Controllers\\" . ucfirst($url[0]);

            if (class_exists($controller)) {
                $this->controller = $controller;

                unset($url[0]);
            } else {
                redirect(404);
            }
        }

        $this->controller = new $this->controller;

        if (isset($url[1])) {
            if(method_exists($this->controller, $url[1])) {
                $this->method = $url[1];

                unset($url[1]);
            } else {
                redirect(404);
            }
        }

        $this->params = $url ? array_values($url) : [];

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        return array_values(
            array_filter(explode('/', trim($uri, '/')))
        );
    }
}