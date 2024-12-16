<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: *");
// index.php inside src/Routes/ folder

use App\Controllers\AccountController;
use App\Controllers\HomeController;
use App\Controllers\KotelnayaController;
// use App\Controllers\KotelnayaController;
use App\Router;


$router = new Router();
define("BASE_DIR", "/src/");
// Define a route for the home page
$router->get("/", HomeController::class, 'index');
$router->post("/api/auth", AccountController::class, "login");
$router->get("/api/auth", AccountController::class, "confirm_auth");
$router->get("/api/EditableList", KotelnayaController::class, "awailable_requests");
$router->post("/api/currentSettings", KotelnayaController::class, "get_current_settings");

// Dispatch routes
$router->dispatch();
