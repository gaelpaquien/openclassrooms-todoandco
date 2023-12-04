# OpenClassrooms - ToDo & Co

## Repository containing the context and deliverables of the project
https://github.com/Galuss1/openclassrooms-archive/tree/main/php-symfony-application-developer/project-8

## Setting up

### Required
1. [PHP ⩾8.1](https://www.php.net/downloads.php)
2. [Composer](https://getcomposer.org/download/)
3. [MySQL](https://www.mysql.com/fr/downloads/)

### Optional
1. [Docker](https://www.docker.com/)
2. [Symfony CLI](https://symfony.com/download)

### Installation
1. **Clone the repository on the main branch**

2. **Create the .env.local file and replace the values of the .env origin file**

3. **Only if you are using Docker, environment installation** \
*You can change the build target file in docker-compose.yml (Dockerfile.dev or Dockerfile.prod)*
```bash
docker-compose up --build
```
Wait a few moments for the environment to fully install. \
The website is accessible at http://localhost:8080 \
Mailhog web interface (SMTP) is accessible at http://localhost:8025 \
The database was created with data at localhost:3310 \
Your installation is complete, you do not need to follow the next steps.

4. **Installing dependencies**
```bash
composer install
```

5. **Setting up the database**
```bash
php bin/console doctrine:database:create
```
```bash
php bin/console doctrine:schema:create
```
```bash
php bin/console doctrine:fixtures:load
```

6. **Start the project**
```bash
php -S 127.0.0.1:8080 -t public
```
```bash
symfony server:start
```

### Other information
*Run the tests (required test environment)*
```bash
php bin/phpunit
```
*Run the tests with coverage (required test environment)*
```bash
php bin/phpunit --coverage-html tests/test-coverage
```
*Change task authors (anonymous author, required dev environment)*
```bash
php bin/console app:update-task-author
```

--- --- ---

### Links
[Website](https://www.formation.todoandco.gaelpaquien.com)\
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-todo-and-co/dashboard)
