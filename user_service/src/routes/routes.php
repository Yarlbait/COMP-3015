<?php

use NewCo\UserService\Controllers\UploadController;
use Symfony\Component\Routing;

$routes = new Routing\RouteCollection();

$routes->add('hello', new Routing\Route('/upload', [
    '_controller' => [new UploadController(), 'upload']
]));

return $routes;
