# Sommaire
- [Sommaire](#sommaire)
- [\[FR\] Guide de l'authentification sur le projet Todo \& Co](#fr-guide-de-lauthentification-sur-le-projet-todo--co)
  - [Introduction](#introduction)
  - [1. Configuration de la sécurité : `config/packages/security.yaml`](#1-configuration-de-la-sécurité--configpackagessecurityyaml)
    - [Introduction](#introduction-1)
    - [Exemple de code](#exemple-de-code)
    - [Conclusion](#conclusion)
  - [2. Controller SecurityController : `src/Controller/SecurityController.php`](#2-controller-securitycontroller--srccontrollersecuritycontrollerphp)
    - [Introduction](#introduction-2)
    - [Exemple de code](#exemple-de-code-1)
    - [Conclusion](#conclusion-1)
  - [3. Authentificateur : `src/Security/LoginFormAuthenticator.php`](#3-authentificateur--srcsecurityloginformauthenticatorphp)
    - [Introduction](#introduction-3)
    - [Exemple de code](#exemple-de-code-2)
    - [Conclusion](#conclusion-2)
  - [4. Entity User : `src/Entity/User.php`](#4-entity-user--srcentityuserphp)
    - [Introduction](#introduction-4)
    - [Méthodes importantes](#méthodes-importantes)
    - [Conclusion](#conclusion-3)
  - [5. Vue Login : `templates/security/login.html.twig`](#5-vue-login--templatessecurityloginhtmltwig)
    - [Introduction](#introduction-5)
    - [Exemple de code](#exemple-de-code-3)
    - [Conclusion](#conclusion-4)
  - [6. Conclusion Générale](#6-conclusion-générale)
- [\[EN\] Todo \& Co Project Authentication Guide](#en-todo--co-project-authentication-guide)
  - [Introduction](#introduction-6)
  - [1. Security Configuration: `config/packages/security.yaml`](#1-security-configuration-configpackagessecurityyaml)
    - [Introduction](#introduction-7)
    - [Code Example](#code-example)
    - [Conclusion](#conclusion-5)
  - [2. SecurityController: src/Controller/SecurityController.php](#2-securitycontroller-srccontrollersecuritycontrollerphp)
    - [Introduction](#introduction-8)
    - [Code Example](#code-example-1)
    - [Conclusion](#conclusion-6)
  - [3. Authenticator: src/Security/LoginFormAuthenticator.php](#3-authenticator-srcsecurityloginformauthenticatorphp)
    - [Introduction](#introduction-9)
    - [Code Example](#code-example-2)
    - [Conclusion](#conclusion-7)
  - [4. User Entity: `src/Entity/User.php`](#4-user-entity-srcentityuserphp)
    - [Introduction](#introduction-10)
    - [Important Methods](#important-methods)
    - [Conclusion](#conclusion-8)
  - [5. Login View: `templates/security/login.html.twig`](#5-login-view-templatessecurityloginhtmltwig)
    - [Introduction](#introduction-11)
    - [Code Example](#code-example-3)
    - [Conclusion](#conclusion-9)
  - [6. General Conclusion](#6-general-conclusion)

# [FR] Guide de l'authentification sur le projet Todo & Co

## Introduction
Ce guide est destiné aux futurs collaborateurs du projet Todo & Co. Il vous aidera à comprendre le fonctionnement du système d'authentification basé sur Symfony 6.

## 1. Configuration de la sécurité : `config/packages/security.yaml`
### Introduction
- **`password_hashers`**: Définit les algorithmes de hachage pour les mots de passe. Cela assure que les mots de passe sont stockés de manière sécurisée.
- **`providers`**: Définit comment les utilisateurs sont chargés à partir de la base de données. Permet de configurer différents types de stockage d'utilisateurs.
- **`firewalls`**: Établit les règles pour l'accès aux différentes parties de l'application. Vous pouvez configurer les firewalls pour différentes routes ou sections.
- **`access_control`**: Contrôle l'accès en fonction des rôles attribués aux utilisateurs. Utilisé pour restreindre l'accès à certaines routes aux utilisateurs ayant un rôle spécifique.

### Exemple de code
```yaml
# config/packages/security.yaml
security:
  password_hashers:
    Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
  providers:
    app_user_provider:
      entity:
        class: App\Entity\User
        property: email
  firewalls:
    dev:
      pattern: ^/(_(profiler|wdt)|css|images|js)/
      security: false
    main:
      provider: app_user_provider
      custom_authenticator: App\Security\LoginFormAuthenticator
      logout:
        path: app_logout
  access_control:
    # - { path: ^/admin, roles: ROLE_ADMIN }
    # - { path: ^/profile, roles: ROLE_USER }

```

### Conclusion
Cette section vous a donné un aperçu de la configuration de la sécurité dans le projet Todo & Co avec Symfony 6. Les points clés à retenir sont les algorithmes de hachage de mots de passe, la manière dont les utilisateurs sont chargés à partir de la base de données, les firewalls, et les contrôles d'accès basés sur les rôles. Ces configurations sont essentielles pour le bon fonctionnement et la sécurité de votre application.

## 2. Controller SecurityController : `src/Controller/SecurityController.php`
### Introduction
- **`login()`**: Cette méthode est responsable de la gestion de la page de connexion. Elle utilise `AuthenticationUtils` pour récupérer les erreurs d'authentification et le dernier nom d'utilisateur saisi, qui seront ensuite passés à la vue.
- **`logout()`**: Bien que cette méthode semble vide, elle est en fait gérée automatiquement par Symfony. Le mécanisme de déconnexion est configuré dans le fichier `security.yaml`.

### Exemple de code
```php
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
```

### Conclusion
Les méthodes `login()` et `logout()` du `SecurityController` sont centrales pour la gestion de la connexion dans Symfony 6. La première gère la page de connexion, tandis que la seconde est gérée automatiquement par Symfony.

## 3. Authentificateur : `src/Security/LoginFormAuthenticator.php`
### Introduction
- `authenticate()`: Cette méthode est appelée lorsqu'un utilisateur tente de se connecter. Elle crée un "passeport" qui encapsule les données de l'utilisateur. Le "passeport" contient des "badges", qui sont des composants responsables de collecter des informations spécifiques sur l'utilisateur (comme le nom d'utilisateur, le mot de passe, les jetons CSRF, etc.).
- `onAuthenticationSuccess()`: Cette méthode est appelée lorsque l'authentification est réussie. Symfony utilise les informations contenues dans le "TokenInterface" pour connaître les rôles et les permissions de l'utilisateur. Ensuite, selon la configuration ou la logique métier, cette méthode redirige l'utilisateur vers une page appropriée.

### Exemple de code
```php
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // Crée un nouveau passeport avec les badges nécessaires pour l'authentification
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Récupère le chemin cible stocké en session si disponible
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Sinon, redirige vers la route par défaut
        return new RedirectResponse($this->urlGenerator->generate('app_default'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
```

### Conclusion
Cette section donne un aperçu de la logique d'authentification personnalisée dans Symfony, en se concentrant sur l'utilisation de "passeports" et "badges" pour la collecte et la validation des données d'authentification. Après une authentification réussie, les rôles et les permissions de l'utilisateur sont encapsulés dans un "TokenInterface", ce qui permet à Symfony de gérer les accès.

## 4. Entity User : `src/Entity/User.php`
### Introduction
- `UserInterface`: Cette interface est fondamentale dans le système de sécurité de Symfony. Elle formalise les méthodes que votre classe `User` doit implémenter pour interagir avec le système de sécurité de Symfony. Les méthodes les plus notables sont :
  - `getUserIdentifier()`: Utilisée pour identifier l'utilisateur.
  - `getRoles()`: Utilisée pour obtenir les rôles attribués à l'utilisateur.
  - `getPassword()`: Utilisée lors de l'authentification pour valider le mot de passe de l'utilisateur.
  - `eraseCredentials()`: Utilisée pour effacer les données sensibles une fois qu'elles ne sont plus nécessaires.
- `PasswordAuthenticatedUserInterface`: Cette interface se focalise sur les utilisateurs avec un mot de passe qui doit être authentifié. Elle ajoute la méthode `getPassword()`, qui est indispensable pour obtenir le mot de passe haché de l'utilisateur et le valider pendant le processus d'authentification.

### Méthodes importantes
- `getEmail()`: Utilisée pour obtenir l'adresse e-mail de l'utilisateur, souvent utilisée comme identifiant unique dans des applications modernes.
- `getRoles()`: Retourne un tableau des rôles attribués à cet utilisateur. Les rôles sont essentiels dans le système de sécurité pour déterminer ce que l'utilisateur est autorisé à faire.
- `getPassword()`: Retourne le mot de passe haché de l'utilisateur. Ce mot de passe est utilisé lors de l'authentification pour valider que l'utilisateur est bien celui qu'il prétend être.

### Conclusion
Dans cette section, nous avons exploré les responsabilités de la classe `User` dans le cadre du système de sécurité de Symfony. Elle implémente plusieurs interfaces essentielles et méthodes pour gérer des aspects tels que l'identification, l'authentification par mot de passe et les rôles de l'utilisateur.

## 5. Vue Login : `templates/security/login.html.twig`
### Introduction
La vue Twig représente le formulaire de connexion dans l'application. Elle utilise le moteur de templates Twig pour rendre les données dynamiques.

### Exemple de code
```html
{% extends 'base.html.twig' %}

{% block body %}
    <!-- Affiche un message d'erreur si l'authentification échoue -->
    {% if error %}
        <div class="alert alert-danger">{{ error.messageKey|trans(error.messageData, 'security') }}</div>
    {% endif %}

    <!-- Si l'utilisateur est déjà connecté -->
    {% if app.user %}
        <div class="mb-3">
            Vous êtes déjà connecté en tant que {{ app.user.userIdentifier }}, <a href="{{ path('app_logout') }}">Déconnexion</a>
        </div>
    {% endif %}

    <!-- Formulaire de connexion -->
    <form method="post">
        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input type="email" value="{{ last_username }}" name="email" id="inputEmail" class="form-control" autocomplete="email" required autofocus>
        </div>

        <div class="form-group">
            <label for="inputPassword">Mot de passe</label>
            <input type="password" name="password" id="inputPassword" class="form-control" autocomplete="current-password" required>
        </div>

        <!-- Protection CSRF -->
        <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">

        <div class="d-flex justify-content-end">
            <button class="btn btn-success" type="submit">Se connecter</button>
        </div>
    </form>
{% endblock %}
```
### Conclusion
- La directive `{% if error %}` vérifie s'il y a une erreur d'authentification et, si c'est le cas, affiche un message d'erreur.
- La directive `{% if app.user %}` vérifie si l'utilisateur est déjà connecté.
- Le champ caché `_csrf_token` ajoute une mesure de sécurité contre les attaques CSRF. Il est généré par Symfony et doit correspondre à celui stocké côté serveur pour que le formulaire soit accepté.

## 6. Conclusion Générale
Cette documentation a abordé les éléments clés de l'authentification dans Symfony 6 : de la configuration dans `security.yaml`, au contrôleur de sécurité et aux entités utilisateur, jusqu'à la vue de connexion. Chaque composant joue un rôle spécifique pour sécuriser l'application, que ce soit en gérant le hachage des mots de passe, en authentifiant les utilisateurs, ou en contrôlant les accès. L'objectif est de fournir une compréhension claire de chaque composant pour faciliter la maintenance et l'évolution du système d'authentification.

<br>
<br>

# [EN] Todo & Co Project Authentication Guide

## Introduction
This guide is intended for future collaborators of the Todo & Co project. It will help you understand the functioning of the authentication system based on Symfony 6.

## 1. Security Configuration: `config/packages/security.yaml`
### Introduction
- **`password_hashers`**: Defines the hashing algorithms for passwords. This ensures that passwords are stored securely.
- **`providers`**: Defines how users are loaded from the database. Allows configuring different types of user storage.
- **`firewalls`**: Establishes rules for access to different parts of the application. You can configure firewalls for different routes or sections.
- **`access_control`**: Controls access based on roles assigned to users. Used to restrict access to certain routes to users with a specific role.

### Code Example
```yaml
# config/packages/security.yaml
security:
  password_hashers:
    Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: 'auto'
  providers:
    app_user_provider:
      entity:
        class: App\Entity\User
        property: email
  firewalls:
    dev:
      pattern: ^/(_(profiler|wdt)|css|images|js)/
      security: false
    main:
      provider: app_user_provider
      custom_authenticator: App\Security\LoginFormAuthenticator
      logout:
        path: app_logout
  access_control:
    # - { path: ^/admin, roles: ROLE_ADMIN }
    # - { path: ^/profile, roles: ROLE_USER }
```

### Conclusion
This section has provided an overview of security configuration in the Todo & Co project with Symfony 6. The key points to remember are password hashing algorithms, how users are loaded from the database, firewalls, and role-based access controls. These configurations are essential for the proper functioning and security of your application.

## 2. SecurityController: src/Controller/SecurityController.php
### Introduction
- `login()`: This method is responsible for managing the login page. It uses AuthenticationUtils to retrieve authentication errors and the last entered username, which are then passed to the view.
- `logout()`: Although this method appears empty, it is actually automatically managed by Symfony. The logout mechanism is configured in the security.yaml file.

### Code Example
```php
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): never
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
```

### Conclusion
The `login()` and `logout()` methods of the SecurityController are central for managing login in Symfony 6. The former handles the login page, while the latter is automatically managed by Symfony.

## 3. Authenticator: src/Security/LoginFormAuthenticator.php
### Introduction
- `authenticate()`: This method is called when a user attempts to log in. It creates a "passport" that encapsulates the user's data. The "passport" contains "badges," which are components responsible for collecting specific information about the user (such as username, password, CSRF tokens, etc.).
- `onAuthenticationSuccess()`: This method is called when authentication is successful. Symfony uses the information contained in the "TokenInterface" to know the roles and permissions of the user. Then, depending on the configuration or business logic, this method redirects the user to an appropriate page.

### Code Example
```php
    public function authenticate(Request $request): Passport
    {
        $email = $request->request->get('email', '');
        $request->getSession()->set(Security::LAST_USERNAME, $email);

        // Create a new passport with the necessary badges for authentication
        return new Passport(
            new UserBadge($email),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // Retrieve the target path stored in session if available
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // Otherwise, redirect to the default route
        return new RedirectResponse($this->urlGenerator->generate('app_default'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
```

### Conclusion
This section provides an overview of custom authentication logic in Symfony, focusing on the use of "passports" and "badges" for collecting and validating authentication data. After successful authentication, the roles and permissions of the user are encapsulated in a "TokenInterface," allowing Symfony to manage access.

## 4. User Entity: `src/Entity/User.php`
### Introduction
- `UserInterface`: This interface is fundamental in Symfony's security system. It formalizes the methods that your `User` class must implement to interact with Symfony's security system. Notable methods include:
  - `getUserIdentifier()`: Used to identify the user.
  - `getRoles()`: Used to obtain roles assigned to the user.
  - `getPassword()`: Used during authentication to validate the user's password.
  - `eraseCredentials()`: Used to erase sensitive data once they are no longer needed.
- `PasswordAuthenticatedUserInterface`: This interface focuses on users with a password that needs to be authenticated. It adds the `getPassword()` method, which is essential for obtaining the hashed password of the user and validating it during the authentication process.

### Important Methods
- `getEmail()`: Used to get the user's email address, often used as a unique identifier in modern applications.
- `getRoles()`: Returns an array of roles assigned to this user. Roles are essential in the security system to determine what the user is allowed to do.
- `getPassword()`: Returns the hashed password of the user. This password is used during authentication to validate that the user is who they claim to be.

### Conclusion
In this section, we explored the responsibilities of the `User` class within the framework of Symfony's security system. It implements several essential interfaces and methods to manage aspects such as identification, password-based authentication, and user roles.

## 5. Login View: `templates/security/login.html.twig`
### Introduction
The Twig view represents the login form in the application. It uses the Twig template engine to render dynamic data.

### Code Example
```html
{% extends 'base.html.twig' %}

{% block body %}
    <!-- Display an error message if authentication fails -->
    {% if error %}
        <div class="alert alert-danger">{{ error.messageKey|trans(error.messageData, 'security') }}</div>
    {% endif %}

    <!-- If the user is already logged in -->
    {% if app.user %}
        <div class="mb-3">
            You are already logged in as {{ app.user.userIdentifier }}, <a href="{{ path('app_logout') }}">Logout</a>
        </div>
    {% endif %}

    <!-- Login form -->
    <form method="post">
        <div class="form-group">
            <label for="inputEmail">Email</label>
            <input type="email" value="{{ last_username }}" name="email" id="inputEmail" class="form-control" autocomplete="email" required autofocus>
        </div>

        <div class="form-group">
            <label for="inputPassword">Password</label>
            <input type="password" name="password" id="inputPassword" class="form-control" autocomplete="current-password" required>
        </div>

        <!-- CSRF Protection -->
        <input type="hidden" name="_csrf_token" value="{{ csrf_token('authenticate') }}">

        <div class="d-flex justify-content-end">
            <button class="btn btn-success" type="submit">Log in</button>
        </div>
    </form>
{% endblock %}
```

### Conclusion
- The `{% if error %}` directive checks for an authentication error and, if present, displays an error message.
- The `{% if app.user %}` directive checks if the user is already logged in.
- The hidden `_csrf_token` field adds a security measure against CSRF attacks. It is generated by Symfony and must match the one stored server-side for the form to be accepted.

## 6. General Conclusion
This documentation has covered the key elements of authentication in Symfony 6: from configuration in security.yaml, to the security controller and user entities, to the login view. Each component plays a specific role in securing the application, whether it's managing password hashing, authenticating users, or controlling access. The goal is to provide a clear understanding of each component to facilitate the maintenance and evolution of the authentication system.