<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once '../config/config.php';

$controller = $_GET['c'] ?? 'auth';
$method     = $_GET['m'] ?? 'login';

$controllerName = ucfirst($controller) . 'Controller';
$controllerFile = "../app/controllers/$controllerName.php";

if (!file_exists($controllerFile)) {
  http_response_code(404);
  die("Controller $controllerName tidak ditemukan");
}

require_once $controllerFile;

$controllerObject = new $controllerName;

if (!method_exists($controllerObject, $method)) {
  http_response_code(404);
  die("Method $method tidak ditemukan di $controllerName");
}

$controllerObject->$method();
