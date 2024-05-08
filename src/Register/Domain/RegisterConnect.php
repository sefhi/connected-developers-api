<?php

declare(strict_types=1);

namespace App\Register\Domain;

use App\Shared\Domain\Aggregate\AggregateRoot;

final class RegisterConnect extends AggregateRoot
{
    private function __construct(
        private string $username1,
        private string $username2,
        private bool $connected,
        private array $organizations,
        private \DateTimeImmutable $registeredAt,
    ) {
    }

    public static function create(
        string $username1,
        string $username2,
        bool $connected,
        array $organizations,
        \DateTimeImmutable $registeredAt
    ): self {
        return new self(
            $username1,
            $username2,
            $connected,
            $organizations,
            $registeredAt
        );
    }

    public function connected(): bool
    {
        return $this->connected;
    }

    public function organizations(): array
    {
        return $this->organizations;
    }

    public function username1(): string
    {
        return $this->username1;
    }

    public function username2(): string
    {
        return $this->username2;
    }

    public function registeredAt(): \DateTimeImmutable
    {
        return $this->registeredAt;
    }
}
