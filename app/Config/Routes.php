<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index'); // Halaman utama
$routes->get('tentang', 'Tracer::tentang'); // Route untuk halaman tentang
$routes->get('kontak', 'Tracer::kontak');   // Route untuk halaman kontak




