<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[MongoDB\Document]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: "string")]
    private string $name;

    #[MongoDB\Field(type: "string")]
    private string $email;

    #[MongoDB\Field(type: "string")]
    private string $password;

    #[MongoDB\Field(type: "string")]
    private ?string $role = null; // Store a single role like "admin", "hr", etc.

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getRoles(): array
    {
        // Convert stored role to Symfony role format
        $roles = [];

        if (!empty($this->role)) {
            $roles[] = 'ROLE_' . strtoupper($this->role);
        }

        $roles[] = 'ROLE_USER'; // Default role for all users
        return array_unique($roles);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function eraseCredentials(): void
    {
        // Optional: clear sensitive temporary data
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
