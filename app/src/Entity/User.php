<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'L\'adresse email ne peut pas être vide.')]
    #[Assert\Email(message: "Cette adresse email n'est pas valide.")]
    #[Assert\Length(
        min: 6,
        max: 255,
        minMessage: 'L\'adresse email doit comporter au moins 6 caractères.',
        maxMessage: 'L\'adresse email doit comporter au maximum 255 caractères.'
    )]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    #[ORM\Column]
    #[Assert\NotBlank(message: 'Le mot de passe ne peut pas être vide.')]
    #[Assert\Length(
        min: 12,
        max: 255,
        minMessage: 'Le mot de passe doit comporter au moins 12 caractères.',
        maxMessage: 'Le mot de passe doit comporter au maximum 255 caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]+$/',
        message: 'Le mot de passe doit comporter au moins 12 caractères, contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.'
    )]
    private ?string $password = null;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: Task::class)]
    private Collection $tasks;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom d\'utilisateur ne peut pas être vide.')]
    #[Assert\Length(
        min: 3,
        max: 25,
        minMessage: 'Le nom d\'utilisateur doit comporter au moins 3 caractères.',
        maxMessage: 'Le nom d\'utilisateur doit comporter au maximum 25 caractères.'
    )]
    private ?string $username = null;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return \array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setAuthor($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        // set the owning side to null (unless already changed)
        if ($this->tasks->removeElement($task) && $task->getAuthor() === $this) {
            $task->setAuthor(null);
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }
}
