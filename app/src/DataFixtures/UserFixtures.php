<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; ++$i) {
            $this->createUser($manager, 'user' . 0 . ($i + 1) . '@email.com', 'user' . 0 . ($i + 1), $i);
        }

        $this->createAdmin($manager);
        $this->createAnonymousUser($manager);

        $manager->flush();
    }

    public function createUser(ObjectManager $manager, string $email, string $username, int $reference): void
    {
        $user = new User();

        $user->setEmail($email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, 'Password?123'));
        $user->setRoles(['ROLE_USER']);
        $user->setUsername($username);

        $this->addReference('user-' . $reference, $user);

        $manager->persist($user);
    }

    public function createAdmin(ObjectManager $manager): void
    {
        $admin = new User();

        $admin->setEmail('admin@email.com');
        $admin->setPassword($this->userPasswordHasher->hashPassword($admin, 'Password?123'));
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setUsername('admin');

        $manager->persist($admin);
    }

    public function createAnonymousUser(ObjectManager $manager): void
    {
        $anonymousUser = new User();

        $anonymousUser->setEmail('anonymous@email.com');
        $anonymousUser->setPassword($this->userPasswordHasher->hashPassword($anonymousUser, 'Password?123'));
        $anonymousUser->setRoles(['ROLE_USER']);
        $anonymousUser->setUsername('anonymous');

        $this->addReference('anonymous', $anonymousUser);

        $manager->persist($anonymousUser);
    }
}
