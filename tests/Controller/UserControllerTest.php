<?php

namespace App\Tests\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class UserControllerTest extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private $userRepository;
    private $user;
    private $admin;
    private $urlGenerator;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->user = $this->userRepository->findOneByEmail('user01@email.com');
        $this->admin = $this->userRepository->findOneByEmail('admin@email.com');
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function testListUsers()
    {
        // User not logged in
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_login'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous devez être connecté pour accéder à cette page.');

        // User logged in but not admin
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_default'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous ne pouvez pas accéder à cette page.');

        // User logged in and admin
        $this->client->loginUser($this->admin);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_list'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }

    public function testCreateUser()
    {
        // Display form
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_create'));
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        // Create user with an existing email
        $form = $crawler->selectButton('Je m\'inscris')->form();
        $form['user[email]'] = 'user01@email.com';
        $form['user[username]'] = 'user01-test';
        $form['user[password][first]'] = 'Password?123';
        $form['user[password][second]'] = 'Password?123';
        $form['user[roles]'] = ['ROLE_USER'];
        $this->client->submit($form);
        $this->assertResponseRedirects($this->urlGenerator->generate('user_create'));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Cette adresse email est déjà utilisée.');

        // Create user with a new email
        $form = $crawler->selectButton('Je m\'inscris')->form();
        $form['user[email]'] = 'new-user-test@email.com';
        $form['user[username]'] = 'new-user-test';
        $form['user[password][first]'] = 'Password?123';
        $form['user[password][second]'] = 'Password?123';
        $form['user[roles]'] = ['ROLE_USER'];
        $this->client->submit($form);
        $this->assertResponseRedirects($this->urlGenerator->generate('app_login'));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', "Votre compte vient d'être créer avec succès.");
    }

    public function testEditUser()
    {
        // User not logged in
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->user->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_login'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous devez être connecté pour modifier un compte.');

        // User is logged in but not the same user and not admin
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->admin->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_default'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous ne pouvez pas modifier ce compte.');

        // User is logged in and is the same user
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->user->getId()]));
        $this->assertResponseIsSuccessful();

        // User is logged in and is admin
        $this->client->loginUser($this->admin);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->user->getId()]));
        $this->assertResponseIsSuccessful();

        // User is logged and he is the profile owner. Try to edit with an existing email (not the same as the user's email)
        $this->client->loginUser($this->user);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->user->getId()]));
        $form = $crawler->selectButton('Modifier mon compte')->form();
        $form['user[email]'] = 'user02@email.com';
        $form['user[username]'] = 'user01-test';
        $form['user[password][first]'] = 'Password?123';
        $form['user[password][second]'] = 'Password?123';
        $form['user[roles]'] = ['ROLE_USER'];
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Oops ! Cette adresse email est déjà utilisée.');

        // User is logged and he is the profile owner. Try to edit with the new email
        $this->client->loginUser($this->user);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('user_edit', ['id' => $this->user->getId()]));
        $form = $crawler->selectButton('Modifier mon compte')->form();
        $form['user[email]'] = 'new-email@email.com';
        $form['user[username]'] = 'user01-test';
        $form['user[password][first]'] = 'Password?123';
        $form['user[password][second]'] = 'Password?123';
        $form['user[roles]'] = ['ROLE_USER'];
        $this->client->submit($form);
        $this->assertResponseRedirects($this->urlGenerator->generate('app_default'));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'Superbe ! Le compte a bien été modifié.');
    }
}
