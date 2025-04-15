<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use App\Document\User;

#[ODM\Document]
class Attendance
{
    #[ODM\Id]
    private $id;

    #[ODM\ReferenceOne(targetDocument: User::class, storeAs: "dbRef")]
    private $user;

    #[ODM\Field(type: "date")]
    private $date;

    #[ODM\Field(type: "string")]
    private $status;

    // Getters and setters...
    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }
}
