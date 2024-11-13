<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'Reset_password_tokens')]
class TokenEntity
{
    #[Id]
    #[Column(type: 'integer', unique: true), GeneratedValue]
    private int $id;

    #[Column(type: 'string', length: 255)]
    private string $token;

    #[Column(type: 'string', length: 255)]
    private string $email;

    #[Column(name: 'expires_at', type: 'string', length: 50)]
    private string $expiresAt;

    // getters setters
    public function getId(): int
    {
        return $this->id;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function setToken(string $token): TokenEntity
    {
        $this->token = $token;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): TokenEntity
    {
        $this->email = $email;
        return $this;
    }

    public function getExpiresAt(): string
    {
        return $this->expiresAt;
    }

    public function setExpiresAt(string $expiresAt): TokenEntity
    {
        $this->expiresAt = $expiresAt;
        return $this;
    }
}