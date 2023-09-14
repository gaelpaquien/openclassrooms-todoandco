# Sommaire
- [Sommaire](#sommaire)
- [\[FR\] Guide de Collaboration sur le projet Todo \& Co](#fr-guide-de-collaboration-sur-le-projet-todo--co)
  - [Introduction](#introduction)
    - [1. Récupérer le contenu de la branche "develop"](#1-récupérer-le-contenu-de-la-branche-develop)
    - [2. Environnement de travail](#2-environnement-de-travail)
    - [3. Créer une nouvelle branche](#3-créer-une-nouvelle-branche)
    - [4. Tester votre code](#4-tester-votre-code)
    - [5. Faire des commits](#5-faire-des-commits)
    - [6. Proposer une Pull Request](#6-proposer-une-pull-request)
    - [7. Conclusion](#7-conclusion)
- [\[EN\] Todo \& Co Project Collaboration Guide](#en-todo--co-project-collaboration-guide)
  - [Introduction](#introduction-1)
    - [1. Fetch the "develop" branch content](#1-fetch-the-develop-branch-content)
    - [2. Working Environment](#2-working-environment)
    - [3. Create a New Branch](#3-create-a-new-branch)
    - [4. Test Your Code](#4-test-your-code)
    - [5. Make Commits](#5-make-commits)
    - [6. Make a Pull Request](#6-make-a-pull-request)
    - [7. Conclusion](#7-conclusion-1)

# [FR] Guide de Collaboration sur le projet Todo & Co

## Introduction
Bienvenue dans l'équipe de développement Todo & Co ! Ce guide a été conçu pour vous offrir une vue d'ensemble des meilleures pratiques à suivre en tant que contributeur sur ce projet. Que vous soyez un développeur chevronné ou un nouveau venu, ces lignes directrices vous aideront à comprendre comment travailler de manière efficace et harmonieuse avec l'équipe.

### 1. Récupérer le contenu de la branche "develop"
Avant toute chose, assurez-vous de travailler avec la dernière version du code sur la branche `develop`.
```bash
git init
git remote add origin git@github.com:Galuss1/openclassrooms-todo-and-co.git
git checkout -b develop
git pull origin develop
```

### 2. Environnement de travail
Assurez-vous de toujours travailler en environnement de développement, sauf lorsque vous réalisez des tests, préférez alors l'environnement de test.
```bash
APP_ENV=dev
APP_ENV=test
```

Nous vous conseillons également de travailler avec Docker, cela vous permet d'être certains d'avoir le même environnement de travail que les autres collaborateurs, tout en vous évitant les installations en local sur votre machine. Demandez de l'aide à un collaborateur pour configurer votre environnement Docker si nécessaire.
```bash
docker-compose up --build -d
docker-compose up -d
docker-compose down
```

### 3. Créer une nouvelle branche
Créez une nouvelle branche avec un nom qui reflète clairement la fonctionnalité sur laquelle vous travaillez.
```bash
git checkout -b feature-name
```

### 4. Tester votre code
Écrivez des tests pour chaque nouvelle fonctionnalité ou modification. Les tests doivent couvrir tous les cas possibles.
Avant de faire des commits, assurez-vous que tous les tests existants et nouveaux passent en environnement de test.
Si des tests échouent, corrigez les erreurs avant de commit.
```bash
php bin/phpunit
php bin/phpunit --coverage-html tests/test-coverage
```

### 5. Faire des commits
Faites des petits commits clair. Ajoutez toujours une description dans vos commits de manière à comprendre de quoi il s'agit (en anglais). Si une issue est liée à votre commit faites le lien avec elle et ajoutez un commentaire si nécessaire (en anglais également).
Après chaque commit vous devez vérifier et corriger si nécessaire les erreurs et avertissements Codacy.
```bash
git add --all
git commit -m "(#10): Clear and concise description of what the commit does in relation to issue #10"
git commit -m "Clear and concise description of what the commit does"
git push -u origin feature-name
```

### 6. Proposer une Pull Request
Lorsque vous êtes satisfait de votre feature, créez une Pull Request contenant votre nouvelle branche et tous ses commits que vous envoyez vers la branche develop (ou autres selon ce qui vous a été demandé).
Allez sur GitHub directement pour proposer votre Pull Request qui sera ensuite revue avec un ou plusieurs autres développeurs avant d'être accepté ou non.

### 7. Conclusion
Merci d'avoir lu ce guide de collaboration pour le projet Todo & Co. Suivre ces étapes et recommandations contribuera non seulement à la qualité du code, mais aussi à la santé générale du projet. Le respect des bonnes pratiques et des workflows établis facilite la collaboration entre tous les membres de l'équipe et conduit à un produit plus robuste.
Si vous avez des questions ou des préoccupations qui n'ont pas été abordées dans ce guide, n'hésitez pas à les soulever avec les membres de l'équipe. Le but est d'évoluer ensemble vers un environnement de travail encore plus efficace et agréable.

<br>
<br>

# [EN] Todo & Co Project Collaboration Guide

## Introduction
Welcome to the Todo & Co development team! This guide is designed to provide you with an overview of the best practices to follow as a contributor to this project. Whether you are an experienced developer or a newcomer, these guidelines will help you understand how to work effectively and harmoniously with the team.

### 1. Fetch the "develop" branch content
First and foremost, make sure you are working with the latest version of the code on the `develop` branch.
```bash
git init
git remote add origin git@github.com:Galuss1/openclassrooms-todo-and-co.git
git checkout -b develop
git pull origin develop
```

### 2. Working Environment
Make sure you always work in a development environment, except when you are running tests, then prefer the test environment.
```bash
APP_ENV=dev
APP_ENV=test
```

We also recommend working with Docker, as it ensures that you have the same working environment as other collaborators, while avoiding local installations on your machine. Ask a colleague for help in setting up your Docker environment if needed.
```bash
docker-compose up --build -d
docker-compose up -d
docker-compose down
```

### 3. Create a New Branch
Create a new branch with a name that clearly reflects the feature you are working on.
```bash
git checkout -b feature-name
```

### 4. Test Your Code
Write tests for each new feature or modification. Tests should cover all possible cases.
Before making commits, make sure all existing and new tests pass in the test environment.
If tests fail, fix the errors before committing.
```
php bin/phpunit
php bin/phpunit --coverage-html tests/test-coverage
```

### 5. Make Commits
Make small, clear commits. Always add a description to your commits to understand what they are about (in English). If an issue is related to your commit, link it and add a comment if necessary (also in English).
After each commit, you must check and correct Codacy errors and warnings if necessary.
```bash
git add --all
git commit -m "(#10): Clear and concise description of what the commit does in relation to issue #10"
git commit -m "Clear and concise description of what the commit does"
git push -u origin feature-name
```

### 6. Make a Pull Request
When you are satisfied with your feature, create a Pull Request containing your new branch and all its commits that you send to the develop branch (or others as requested).
Go directly to GitHub to make your Pull Request, which will then be reviewed by one or more other developers before being accepted or not.

### 7. Conclusion
Thank you for reading this collaboration guide for the Todo & Co project. Following these steps and recommendations will contribute not only to the quality of the code but also to the overall health of the project. Adhering to best practices and established workflows facilitates collaboration among all team members and leads to a more robust product.
If you have questions or concerns that have not been addressed in this guide, feel free to raise them with team members. The goal is to evolve together towards an even more effective and enjoyable working environment.