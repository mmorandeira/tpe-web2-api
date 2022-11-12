<?php

namespace Moran;

use Moran\Controller\ApiController;
use Moran\Controller\ApiAuthController;

error_reporting(E_ERROR | E_PARSE);

require_once('./Router.php');
require_once('./controller/ApiController.php');
require_once('./controller/ApiAuthController.php');

$API_VERSION = 'v1';
$router = new Router();

// Gastos
$router->addRoute("api/$API_VERSION/gastos", 'GET', ApiController::class, 'getExpenses');
$router->addRoute("api/$API_VERSION/gastos/:id", 'GET', ApiController::class, 'getExpense');
$router->addRoute("api/$API_VERSION/gastos/:id", 'DELETE', ApiController::class, 'deleteExpense');
$router->addRoute("api/$API_VERSION/gastos/:id", 'PUT', ApiController::class, 'putExpense');
$router->addRoute("api/$API_VERSION/gastos", 'POST', ApiController::class, 'addExpense');

// Autorizacion
$router->addRoute("api/$API_VERSION/authorization", 'POST', ApiAuthController::class, 'getToken');

$router->route($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
