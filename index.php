<?php
// index.php - Entry point of the application
require "./debug/error_notice.php";
// Require the Composer autoloader to autoload classes
require './vendor/autoload.php';
// var_dump(spl_classes());
// debug($_POST);
// Create an instance of Router to handle routing
$router = require './src/Routes/index.php';