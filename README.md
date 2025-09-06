# OpenClassrooms - ToDo & Co

## Description
Audit (quality and performance) of an existing application to identify the tasks necessary for its improvement.\
Optimization and reduction of the technical debt of this application.\
This project was carried out as part of a training course. Below you will find the link to the initial repository containing all the training deliverables.

## Setting up

### Installation
1. **Clone the repository on the master branch**

2. **Create the .env.local file and replace the values of the .env origin file**

3. **Install your environment** \
```bash
docker-compose up --build
```

### Other information
*Run the tests*
```bash
php bin/phpunit
```

*Change task authors (anonymous author, required dev environment)*
```bash
php bin/console app:update-task-author
```

--- --- ---

### Links
[Website](https://todoandco.gaelpaquien.com/)\
[Old repository containing training deliverables](https://github.com/gaelpaquien/openclassrooms-archive/tree/main/php-symfony-application-developer/project-8)
