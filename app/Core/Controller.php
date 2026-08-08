<?php

namespace Core;

class Controller
{
    public function model($model) {
        $class = "Models\\" . ucfirst($model);

        return new $class();
    }

    public function view($view, $data = []) {
        require_once("../app/views/$view.php");
    }
}