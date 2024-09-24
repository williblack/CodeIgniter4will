<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('index2/', 'Home::index2');
$routes->get('prueba/', 'Prueba::index');
$routes->get('prueba/productos', 'Prueba::productos');
$routes->get('prueba/producto/(:any)', 'Prueba::producto/$1');
$routes->get('prueba/almacen/(:any)/(:any)', 'Prueba::almacen/$1/$2');
$routes->get('pagina/', 'Pagina::index');

