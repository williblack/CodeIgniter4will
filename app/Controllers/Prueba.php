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
    public function producto($producto): string
    {
       return "hola he recibido una solicituid por el producto $producto";
    }
    public function almacen($producto,$cantidad): string
    {
       return "hola he recibido una solicituid por el producto $producto por una cantidad de $cantidad";

    }
}