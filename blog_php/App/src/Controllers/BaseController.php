<?php

namespace App\Controllers;

abstract class BaseController
{
    public function render($view, $data = [])
    {
        extract($data); 
        $this->WriteHeader();
        require_once dirname(__DIR__, 2). "/Views/$view.php";
        $this->WriteFooter();
    }

    public function WriteHeader()
    {
        require_once dirname(__DIR__, 2). "/Views/Header.php";
    }

    public function WriteFooter()
    {
        require_once dirname(__DIR__, 2). "/Views/Footer.php";
    }
}