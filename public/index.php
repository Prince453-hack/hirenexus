<?php
require "../helpers.php";


$uri = $_SERVER["REQUEST_URI"];
$method = $_SERVER["REQUEST_METHOD"];



require basePath("Router.php");

$router = new Router();
$routes = require basePath("routes.php");

$router->route($uri, $method);
