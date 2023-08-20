# PHP/Symfony application developer training - OpenclassRooms (P8)

## Improve an existing application: ToDo & Co

### Skills assessed
1. Implement unit and functional tests
2. Implement new functionalities within an application already initiated by following a clear collaboration plan
3. Read and transcribe the operation of a piece of code written by other developers
4. Produce a test execution report
5. Analyze code quality and application performance
6. Establish a plan to reduce the technical debt of an application
7. Provide corrective patches when testing suggests so
8. Propose a series of improvements

--- --- ---

### Setting up the project

#### Required
1. [PHP 8.2](https://www.php.net/downloads.php)
2. [Symfony CLI](https://symfony.com/download)
3. [Composer](https://getcomposer.org/download/)
4. [MySQL](https://www.mysql.com/fr/downloads/)
5. [Docker](https://www.docker.com/) (*optional*)

#### Installation
1. **Clone the repository**
```bash
git clone https://github.com/Galuss1/openclassrooms-todo-and-co.git
```
<br />

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
# Choose one of the transports below
# MESSENGER_TRANSPORT_DSN=amqp://guest:guest@localhost:5672/%2f/messages
# MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages
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
# You also need to uncomment in docker-compose.yml and Dockerfile, and add your newrelic license key here and in Dockerfile
#NEW_RELIC_APP_NAME=#"App Name"#
#NEW_RELIC_LICENSE_KEY=#newrelic_license_key#
#NRIA_LICENSE_KEY=#newrelic_license_key#
#NEW_RELIC_LOG_LEVEL=#newrelic_log_level(default: info)#
#NEW_RELIC_DAEMON_LOG_LEVEL=#newrelic_daemon_log_level(default: info)#
###< docker/newrelic ###
```
<br />

3. **If you are using docker, install your environment**
```bash
docker-compose up --build -d
```
<br />

4. **Installing dependencies**
```bash
composer install
```
<br />

5. **Setting up the database**<br /><br />  *If you are using docker, the first command is not necessary*
```bash
php bin/console doctrine:database:create
```
```bash
php bin/console doctrine:schema:create
```
```bash
php bin/console doctrine:fixtures:load
```
<br />

6. **Start the project**
```bash
symfony server:start
```
```bash
php -S 127.0.0.1:8080 -t public
```

7. **Other information**<br />
*Run the tests (use the test environment)*
```bash
php bin/phpunit
```
*Run the tests with coverage (use the test environment)*
```bash
php bin/phpunit --coverage-html tests/test-coverage
```
*Use the command to change task authors (anonymous author)*
```bash
php bin/console app:update-task-author
```

 --- --- ---

### Links
[Link to the website]() SOON <br />
[Codacy Review](https://app.codacy.com/gh/Galuss1/openclassrooms-todo-and-co/dashboard)
