# Project shop_conta_minecraft

**Required**: PHP bases, Object Oriented Programmation, MVC pattern

## Stape 1 - Architecture

The app have the following structure

```
Shop_conta_minecraft
    public/
        .htaccess
        index.php
        style.css
    src/
        config/
            config.php
        Controllers/
            AdminController.php
            CategoryController.php
            Controller.php
            ItemController.php
            MembreController.php
            TeamController.php
        Models/
            Admin.php
            AdminManager.php
            Category.php
            CategoryManager.php
            Item.php
            ItemManager.php
            Manager.php
            Membre.php
            MembreManager.php
            Team.php
            TeamManager.php
        scss/
            style.scss
        Views/
            Team/
                edit.php
                login.php
                register.php
            404.php
            index.php
            layout.php
        helper.php
        Route.php
        Router.php
        Validator.php
```

## Stape 2 - Composer and autolod

- Init the directory like a composer project

```shell
$ composer init  # create the file composer.json
$ composer install # install dependencies and autoload
```

- Fill the composer.json with autoload rule

```json
"autoload": {
    "psr-4": {
        "RootName\\": "src/"
    }
}
```

- Reset autoload

```shell
$ composer dump-autoload
```

- Launch the project locally

```shell
$ cd public
$ php -S localhost:8000
```
or
```shell
$ php -S localhost:8000 -t public
```

## Stape 3 - The router

To create a route, you have to type this code into the file index.php in the public directory:
```php
$router = new Project\Router($_SERVER["REQUEST_URI"]);

//This line for a GET route
$router->get('route', 'Controller@method');

//This line for a POST route
$router->post('route', 'Controller@method');
 ```

There are some examples of routes (not in this project):

- "/dashboard/:todoid/task/taskid, GET
- "/dashboard/task/nouveau, GET
- "/dashboard/task/nouveau, POST
- "/dashboard/:todoid/task/:taskid, POST
- "/dashboard/:todoid/task/:taskid/delete GET

The :properties are the route's queries you cannot send the queries by ?param=something&...

## Stape 4 - Models

Models regroup all the methods interacting with the database
- Managers: methods that direct interact, requests sql
- Models: represents a table line

In this project managers must be named like SomethingManager and extends Manager, the models don't have a specific name 

## Stape 5 - Controllers

Controllers regroup all the methods that don't interact with the database

In this project controllers must be named like SomethingController, extends Controller, and have a constructor that instantiate the manager and call the parent's contructor

## Stape 6 - Views

Views are files that are used for the frontend dev, they contain html/js/php

Views are called by the controllers with the method Controller::render('viewPath', data) (the data is optional it serves to send the variables values to the view)