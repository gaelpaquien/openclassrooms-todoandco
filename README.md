# OpenClassrooms - Improve an existing application : ToDo & Co

## Repository containing the context and deliverables of the project
https://github.com/Galuss1/openclassrooms-archive/tree/main/php-symfony-application-developer/project-8

## Setting up

### Required
1. [PHP 8.1](https://www.php.net/downloads.php)
2. [Composer](https://getcomposer.org/download/)
3. [MySQL](https://www.mysql.com/fr/downloads/)

### Optional
1. [Docker](https://www.docker.com/)
2. [Symfony CLI](https://symfony.com/download)

### Installation
1. **Clone the repository on the main branch**
<br>

2. **Create the .env.local file and replace the values of the .env origin file**
```bash
###> symfony/framework-bundle ###
APP_ENV=#env|prod|test#
APP_SECRET=#secret#
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL=#"mysql://user:password@host:port/database?serverVersion=15&charset=utf8"#
###< doctrine/doctrine-bundle ###

###> symfony/messenger ###
MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=0
###< symfony/messenger ###

###> symfony/mailer ###
MAILER_DSN=#smtp://host:1025
###< symfony/mailer ###

###> docker/database ###
DATABASE_HOST=#database_host#
MYSQL_DATABASE=#database_name#
MYSQL_ROOT_PASSWORD=#database_root_password#
MYSQL_USER=#database_user#
MYSQL_PASSWORD=#database_user_password#
MYSQL_DATABASE_TEST=#database_test_name#
###< docker/database ###

###> docker/newrelic ###
# Uncomment all lines if you want to use New Relic Agent
# You also need to uncomment in docker-compose.yml and Dockerfile
#NEW_RELIC_APP_NAME=#"App Name"#
#NEW_RELIC_LICENSE_KEY=#newrelic_license_key#
#NRIA_LICENSE_KEY=#newrelic_license_key#
#NEW_RELIC_LOG_LEVEL=#newrelic_log_level(default: info)#
#NEW_RELIC_DAEMON_LOG_LEVEL=#newrelic_daemon_log_level(default: info)#
###< docker/newrelic ###
```
<br>

3. **If you are using docker, install your environment and start the project**
```bash
docker-compose up --build -d
```
<br>

4. **Installing dependencies**
```bash
composer install
```
<br>

5. **Setting up the database**<br />
*If you are using docker, the first command is not necessary and the database "training_todoandco" is already created without the data at localhost:3310*
```bash
php bin/console doctrine:database:create
```
```bash
php bin/console doctrine:schema:create
```
```bash
php bin/console doctrine:fixtures:load
```
<br>

6. **Start the project**<br>
*If you are using docker, the project is already accessible at http://localhost:8080*
```bash
php -S 127.0.0.1:8080 -t public
```
```bash
symfony server:start
```
<br>

### Other information
*Run the tests (required test environment)*
```bash
php bin/phpunit
```
*Run the tests with coverage (required test environment)*
```bash
php bin/phpunit --coverage-html tests/test-coverage
```
*Use this command to change task authors (anonymous author)*
```bash
php bin/console app:update-task-author
```
<br>

--- --- ---

### Links
[Website](https://www.formation.todoandco.gaelpaquien.com)\
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-todo-and-co/dashboard)
