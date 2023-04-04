<?php
session_start();
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(E_ALL ^ E_DEPRECATED);

require_once "env.php";
require_once "vendor/autoload.php";

Use Tools\Router;

$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$actual_link = explode("/", $actual_link);

$class = $actual_link[count($actual_link)-2];

$function = $actual_link[count($actual_link)-1];

if (sizeof(explode("?", $function)) > 1) {
  $function =(explode("?", $function))[0];
}
$method = getenv('REQUEST_METHOD');

if($class === "coeurmometre"){
  $class = NULL;
}

$O_router = new Router($class, $function);
