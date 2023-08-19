<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;

class UserService
{
    public function __construct(private UserRepository $userRepository, private EntityManagerInterface $em, private Security $security)
    {
        $this->userRepository = $userRepository;
        $this->em = $em;
        $this->security = $security;
    }

    public function userIsLogged(): bool
    {
        return $this->security->getUser() instanceof \Symfony\Component\Security\Core\User\UserInterface;
    }

    public function userIsAdmin($user): bool
    {
        return \in_array('ROLE_ADMIN', $user->getRoles(), true);
    }

    public function isSameUser($user): bool
    {
        return $this->security->getUser() === $user;
    }

    public function emailExist($email): bool
    {
        return $this->userRepository->findOneBy(['email' => $email]) instanceof \App\Entity\User;
    }

    public function emailTakenByAnotherUser($email, $user): bool
    {
        $foundUser = $this->userRepository->findOneBy(['email' => $email]);

        return $foundUser && $foundUser->getId() !== $user->getId();
    }

    public function list(): array
    {
        return $this->userRepository->findAll();
    }

    public function create(FormInterface $form, UserPasswordHasher $userPasswordHasher, User $user): void
    {
        $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('password')->getData()));
        $user->setRoles($form->get('roles')->getData());

        $this->em->persist($user);
        $this->em->flush();
    }

    public function edit(FormInterface $form, UserPasswordHasher $userPasswordHasher, User $user): void
    {
        $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('password')->getData()));

        $this->em->flush();
    }
}
