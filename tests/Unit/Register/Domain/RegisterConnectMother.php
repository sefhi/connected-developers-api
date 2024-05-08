<?php

declare(strict_types=1);

namespace App\Tests\Unit\Register\Domain;

use App\Register\Domain\RegisterConnect;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class RegisterConnectMother
{
    public static function create(
        UuidInterface $id,
        string $username1,
        string $username2,
        bool $connected,
        array $organizations,
        \DateTimeImmutable $registeredAt
    ): RegisterConnect {
        return RegisterConnect::create(
            $id,
            $username1,
            $username2,
            $connected,
            $organizations,
            $registeredAt
        );
    }

    public static function random(): RegisterConnect
    {
        return self::create(
            Uuid::uuid7(),
            'username1',
            'username2',
            true,
            ['organization1', 'organization2'],
            new \DateTimeImmutable()
        );
    }
}
