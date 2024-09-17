<?php

namespace App\Controllers;

class Prueba extends BaseController
{
    public function index(): string
    {
       return "hola desde prueba";
    }
    public function productos(): string
    {
       return "hola desde productos";
    }
}