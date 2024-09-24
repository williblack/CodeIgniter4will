<?php

namespace App\Controllers;

class Pagina extends BaseController
{
    public function index(): string
    {
        $head= view("head");
        $header= view("header");
        $main= view("main");
        $end= view("end");
        $html= $head.$header.$main.$end;
        return $html;
    }
    
}
