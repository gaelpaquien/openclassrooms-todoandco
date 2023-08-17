<?php

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
        return $this->security->getUser() !== null;
    }

    public function userIsAdmin($user): bool
    {
        return in_array('ROLE_ADMIN', $user->getRoles());
    }

    public function isSameUser($user): bool
    {
        return $this->security->getUser() === $user;
    }

    public function emailExist($email): bool
    {
        return $this->userRepository->findOneBy(['email' => $email]) !== null;
    }

    public function isSameEmail($email, $user): bool
    {
        return $email === $user->getEmail();
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

    public function canEdit($user): bool
    {
        return $this->isSameUser($user) || $this->userIsAdmin($this->security->getUser());
    }

    public function edit(FormInterface $form, UserPasswordHasher $userPasswordHasher, User $user): void
    {
        $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('password')->getData()));

        $this->em->flush();
    }
}