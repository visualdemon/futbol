<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->post('/api/login', 'AuthController@login');

// Rutas protegidas con middleware
$router->group(['middleware' => 'auth.admin'], function () use ($router) {
    $router->get('/api/partidos', 'PartidoController@indexPrivado'); // Si necesitas lógica diferente
    $router->post('/api/partidos/guardar', 'PartidoController@guardar');
    $router->delete('/api/partidos/eliminar/{fecha}', 'PartidoController@eliminar');
});

// Ruta pública (sin token)
$router->get('/api/partidos/publico', 'PartidoController@index');

// Página raíz (opcional)
$router->get('/', function () use ($router) {
    return $router->app->version();
});

