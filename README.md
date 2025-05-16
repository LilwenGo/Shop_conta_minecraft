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

## Launch project with Docker

If you want to deploy the app with Docker, you must follow some steps.

First of all create a `.env` file based on the `.env-sample` and modify the vairables values.

After that, you can run the following command:
```bash
docker compose up
```

And then, you can initialise the database structure with the following command:
```bash
docker-compose exec db mysql -uuser -p database-name < DB/shop_conta_minecraft.sql
```
> ![IMPORTANT]
> You must replace `-uuser` by the name of your database user (-ujames, -ujack)
> `database-name` is the name of your database in the `.env` file

Once this is done, you can see your app on `http://localhost:8080` 