<?php

declare(strict_types=1);

namespace App\Connect\User\Domain;

final class UserConnectionNotFoundException extends \DomainException
{
    public function __construct(
        private readonly bool $connected,
        string $message = ''
    ) {
        parent::__construct($message);
    }

    public static function notConnected(): self
    {
        return new self(false, message: 'Users are not connected');
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }
}
