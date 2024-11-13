<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'Users')]
class UserEntity
{
    #[Id]
    #[Column(type: 'integer', unique: true), GeneratedValue]
    private int $id;

    #[Column(type: 'string', length: 255)]
    private string $name;

    #[Column(type: 'string', length: 255, unique: true)]
    private string $email;

    #[Column(type: 'string', length: 255)]
    private string $password;

    #[OneToMany(mappedBy: 'userId', targetEntity: AccountEntity::class, cascade: ['persist', 'remove'])]
    private Collection $transactions;

    // getters setters
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): UserEntity
    {
        $this->password = $password;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): UserEntity
    {
        $this->name = $name;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): UserEntity
    {
        $this->email = $email;
        return $this;
    }

    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(AccountEntity $transaction): self
    {
        $this->transactions->add($transaction);

        return $this;
    }
}