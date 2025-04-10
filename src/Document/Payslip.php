<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Payslip
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\ReferenceOne(targetDocument: User::class)]
    private $user; // FIXED: use `$user`, not `$user_id`

    #[MongoDB\Field(type: 'string')]
    private $month;

    #[MongoDB\Field(type: 'string')]
    private $amount;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user; // FIXED
    }

    public function setUser(?User $user): self
    {
        $this->user = $user; // FIXED
        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(string $month): self
    {
        $this->month = $month;
        return $this;
    }

    public function getAmount(): ?string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): self
    {
        $this->amount = $amount;
        return $this;
    }
}
