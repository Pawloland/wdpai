<?php

// enable all php exceptions to be caught by the debugger and passed to phpstorm to stop on the line that caused them
// https://www.jetbrains.com/help/phpstorm/debugging-with-php-exception-breakpoints.html#php-exception-breakpoints

require_once 'Routing.php';
session_start(); //to handle redirection messages
//require_once 'src/components/SecurityComponent.php';
//$sc = new SecurityComponent();
//$sc->updateAuthCookie();

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);


Router::get('', 'DefaultController');
Router::post('login', 'SecurityController');
Router::post('adminLogin', 'SecurityController');
Router::get('logout', 'SecurityController');
Router::get('adminLogout', 'SecurityController');
Router::post('register', 'SecurityController');
Router::get('select_place', 'ReservationController');
Router::post('getDiscount', 'ReservationController');
Router::post('addReservation', 'ReservationController');
Router::post('upload', 'UploadController');
Router::post('admin_panel', 'UploadController');
Router::post('addScreening', 'ScreeningController');
Router::post('removeMovie', 'AdminController');
Router::post('removeReservation', 'AdminController');
Router::post('removeScreening', 'AdminController');
Router::post('removeClient', 'AdminController');
Router::post('removeUser', 'AdminController');

Router::run($path);
