<?php

namespace Moran;

use Moran\Controller\ApiController;

error_reporting(E_ERROR | E_PARSE);

require_once('./Router.php');
require_once('./controller/ApiController.php');


$API_VERSION = 'v1';
$router = new Router();

$router->addRoute("api/$API_VERSION/gastos", 'GET', ApiController::class, 'getExpenses');
$router->addRoute("api/$API_VERSION/gastos/:id", 'GET', ApiController::class, 'getExpense');
$router->addRoute("api/$API_VERSION/gastos/:id", 'DELETE', ApiController::class, 'deleteExpense');
$router->addRoute("api/$API_VERSION/gastos", 'POST', ApiController::class, 'addExpense');

$router->route($_GET['resource'], $_SERVER['REQUEST_METHOD']);
