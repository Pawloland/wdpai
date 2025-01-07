<?php

// enable all php exceptions to be caught by the debugger and passed to phpstorm to stop on the line that caused them
// https://www.jetbrains.com/help/phpstorm/debugging-with-php-exception-breakpoints.html#php-exception-breakpoints

require 'Routing.php';
session_start(); //to handle redirection messages

$path = trim($_SERVER['REQUEST_URI'], '/');
$path = parse_url($path, PHP_URL_PATH);


Router::get('', 'DefaultController');
Router::post('upload', 'UploadController');
Router::post('admin_panel', 'UploadController');
Router::get('projects', 'ProjectController');
Router::post('login', 'SecurityController');
Router::post('addProject', 'ProjectController');
Router::post('register', 'SecurityController');
Router::post('search', 'ProjectController');
Router::get('like', 'ProjectController');
Router::get('dislike', 'ProjectController');
Router::get('map', 'MapController');
Router::get('places', 'MapController');

Router::run($path);
