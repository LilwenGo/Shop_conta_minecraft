<?php

session_start();

require '../src/config/config.php';
require '../vendor/autoload.php';
require SRC . 'helper.php';

$router = new Project\Router($_SERVER["REQUEST_URI"]);
//Home
$router->get('/', "TeamController@index");

//Teams
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
$router->get('/membres/:id/', "MembreController@show");
$router->post('/membres/create/', "MembreController@store");
$router->post('/membres/:id/update/', "MembreController@update");
$router->get('/membres/:id/delete/', "MembreController@delete");

//Items
$router->get('/items/', "ItemController@index");
$router->get('/items/:id/', "ItemController@show");
$router->post('/items/create/', "ItemController@store");
$router->post('/items/:id/update/', "ItemController@update");
$router->post('/items/:id/updateCategory/', "ItemController@updateCategory");
$router->get('/items/:id/delete/', "ItemController@delete");

//Solds
$router->get('/solds/:filter/:id/index/', "SoldController@index");
$router->post('/solds/create/', "SoldController@store");
$router->post('/solds/:id/update/', "SoldController@update");
$router->get('/solds/:id/delete/', "SoldController@delete");

//Categories
$router->get('/categories/', "CategoryController@index");
$router->get('/categories/:id/', "CategoryController@show");
$router->post('/categories/create/', "CategoryController@store");
$router->post('/categories/:id/update/', "CategoryController@update");
$router->get('/categories/:id/delete/', "CategoryController@delete");

$router->run();
