<?php

use NewCo\FileGateway\Controllers\FileGatewayController;
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('hello', new Routing\Route('/save', [
    '_controller' => [new FileGatewayController(), 'save']
]));

return $routes;
