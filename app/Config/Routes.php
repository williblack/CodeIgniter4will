<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('index2/', 'Home::index2');
$routes->get('prueba/', 'Prueba::index');
$routes->get('prueba/productos', 'Prueba::productos');

