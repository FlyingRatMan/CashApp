<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'Account')]
class AccountEntity
{
    #[Id]
    #[Column(type: 'integer', unique: true), GeneratedValue]
    private int $id;

    #[ManyToOne(targetEntity: UserEntity::class, inversedBy: 'transactions')]
    #[Column(name: 'user_id', type: 'string', length: 255)]
    private int $userId;

    #[Column(type: 'float')]
    private float $amount;

    #[Column(type: 'string')]
    private string $date;

    // getters setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function setUserId(int $userId): AccountEntity
    {
        $this->userId = $userId;
        return $this;
    }

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): AccountEntity
    {
        $this->date = $date;
        return $this;
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): AccountEntity
    {
        $this->amount = $amount;
        return $this;
    }
}