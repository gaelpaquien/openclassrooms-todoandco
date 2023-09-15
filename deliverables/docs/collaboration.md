# Guide de Collaboration sur le projet Todo & Co

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
