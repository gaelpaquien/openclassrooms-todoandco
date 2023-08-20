<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends WebTestCase
{
    private KernelBrowser|null $client = null;
    private $userRepository;
    private $user;
    private $admin;
    private $taskRepository;
    private $tasks;
    private $tasksOrdered;
    private $urlGenerator;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->userRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(User::class);
        $this->user = $this->userRepository->findOneByEmail('user01@email.com');
        $this->admin = $this->userRepository->findOneByEmail('admin@email.com');
        $this->taskRepository = $this->client->getContainer()->get('doctrine.orm.entity_manager')->getRepository(Task::class);
        $this->tasks = $this->taskRepository->findAll();
        $this->tasksOrdered = $this->taskRepository->findAllOrderBy('createdAt', 'DESC');
        $this->urlGenerator = $this->client->getContainer()->get('router.default');
    }

    public function listTasks()
    {
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_list'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        if (count($this->tasksOrdered) > 0) {
            foreach ($this->tasksOrdered as $task) {
                $this->assertSelectorTextContains('h5.card-title a', $task->getTitle());
            }
        } else {
            $this->assertSelectorTextContains('div.alert.alert-warning', 'Il n\'y a pas encore de tâche enregistrée.');
        }
    }

    public function testCreateTask()
    {
        // User is not logged in
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_login'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous devez être connecté pour créer une tâche.');

        // User is logged in
        $this->client->loginUser($this->user);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_create'));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        // Create task
        $form = $crawler->filter('.btn.btn-success')->form();
        $form['task[title]'] = 'Task de test';
        $form['task[content]'] = 'Content de la task de test';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a été bien été ajoutée.');
    }

    public function testEditTask()
    {
        // User is not logged in
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => $this->tasks[0]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_login'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous devez être connecté pour modifier une tâche.');

        // User is logged in but not the author of the task
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => $this->tasks[1]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('task_list'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous ne pouvez pas modifier une tâche dont vous n\'êtes pas l\'auteur.');

        // User is logged in and is the author of the task
        $this->client->loginUser($this->user);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => $this->tasks[0]->getId()]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->filter('.btn.btn-success')->form();
        $form['task[title]'] = 'Task de test modifiée';
        $form['task[content]'] = 'Content de la task de test modifiée';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été modifiée.');

        // Task author is anonymous and user is logged in but not admin
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => $this->tasks[6]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('task_list'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Seul un administrateur peut modifier une tâche anonyme.');

        // Task author is anonymous and user is logged in and admin
        $this->client->loginUser($this->admin);
        $crawler = $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_edit', ['id' => $this->tasks[6]->getId()]));
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $form = $crawler->filter('.btn.btn-success')->form();
        $form['task[title]'] = 'Task anonyme modifiée';
        $form['task[content]'] = 'Content de la task anonyme modifiée';
        $this->client->submit($form);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été modifiée.');
    }

    public function testToggleTask()
    {
        // User is not logged in
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => $this->tasks[0]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_login'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous devez être connecté pour modifier l\'état d\'une tâche.');

        // User is logged in but not the author of the task
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => $this->tasks[1]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('task_list'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous ne pouvez pas modifier l\'état d\'une tâche dont vous n\'êtes pas l\'auteur.');

        // User is logged in and is the author of the task
        $this->client->loginUser($this->user);
        $initialState = $this->tasks[0]->isDone();
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => $this->tasks[0]->getId()]));
        $this->client->followRedirect();
        if ($initialState) {
            $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été marquée comme non terminée.');
        } else {
            $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été marquée comme terminée.');
        }

        // Task author is anonymous, user is logged in but not admin
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => $this->tasks[6]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('task_list'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Seul un administrateur peut modifier l\'état d\'une tâche dont l\'auteur est anonyme.');

        // Task author is anonymous, user is logged in and admin
        $this->client->loginUser($this->admin);
        $initialStateAdmin = $this->tasks[6]->isDone();
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_toggle', ['id' => $this->tasks[6]->getId()]));
        $this->client->followRedirect();
        if ($initialStateAdmin) {
            $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été marquée comme non terminée.');
        } else {
            $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été marquée comme terminée.');
        }
    }

    public function testDeleteTask()
    {
        // User is not logged in
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $this->tasks[0]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('app_login'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous devez être connecté pour supprimer une tâche.');

        // User is logged in but not the author of the task
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $this->tasks[1]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('task_list'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Vous ne pouvez pas supprimer une tâche dont vous n\'êtes pas l\'auteur.');

        // User is logged in and is the author of the task
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $this->tasks[0]->getId()]));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été supprimée.');

        // Task author is anonymous, user is logged in but not admin
        $this->client->loginUser($this->user);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $this->tasks[6]->getId()]));
        $this->assertResponseRedirects($this->urlGenerator->generate('task_list'), 302);
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-danger', 'Seul un administrateur peut supprimer une tâche dont l\'auteur est anonyme.');

        // Task author is anonymous, user is logged in and admin
        $this->client->loginUser($this->admin);
        $this->client->request(Request::METHOD_GET, $this->urlGenerator->generate('task_delete', ['id' => $this->tasks[6]->getId()]));
        $this->client->followRedirect();
        $this->assertSelectorTextContains('div.alert.alert-success', 'La tâche a bien été supprimée.');
    }
}
