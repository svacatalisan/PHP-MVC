<?php

include_once 'Config/config.php';
include_once('Autoloader.php');
global $config;

$frontController = new FrontController();
$frontController->run();

?>
