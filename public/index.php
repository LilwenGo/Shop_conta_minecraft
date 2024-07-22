<?php

session_start();

require '../src/config/config.php';
require '../vendor/autoload.php';
require SRC . 'helper.php';

$router = new Project\Router($_SERVER["REQUEST_URI"]);
//Home
$router->get('/', "TeamController@index");

//Team
$router->get('/team/', "TeamController@show");
$router->get('/team/attempt/', "TeamController@update");
$router->get('/team/logout/', "TeamController@logout");
$router->get('/team/login', "TeamController@showLogin");
$router->post('/team/login/attempt/', "TeamController@login");
$router->get('/team/register', "TeamController@showRegister");
$router->post('/team/register/attempt/', "TeamController@register");

//Admins
$router->get('/admins/', "AdminController@index");
$router->post('/admins/create/', "AdminController@store");
$router->post('/admins/:id/update/', "AdminController@update");
$router->get('/admins/:id/delete/', "AdminController@delete");
$router->get('/admins/login', "AdminController@showLogin");
$router->post('/admins/login/attempt/', "AdminController@login");
$router->get('/admins/logout', "AdminController@logout");

//Membres
$router->get('/membres/', "MembreController@index");
$router->post('/membres/create/', "MembreController@store");
$router->post('/membres/:id/update/', "MembreController@update");
$router->post('/membres/:id/delete/', "MembreController@delete");

$router->run();
