<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class UserRepositoryTest extends KernelTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testUpgradePasswordWithUserInstance()
    {
        $repo = $this->entityManager->getRepository(User::class);

        $user = new User();
        $user->setEmail('email-test@email.com');
        $user->setUsername('username-test');
        $user->setPassword('old_password');
        $newPassword = 'new_hashed_password';

        $repo->upgradePassword($user, $newPassword);

        $this->assertSame($newPassword, $user->getPassword());
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        $this->entityManager = null;
    }
}
