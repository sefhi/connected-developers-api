<?php

declare(strict_types=1);

namespace App\Tests\Unit\Register\Application\Event;

use App\Connect\User\Domain\ConnectedRegisteredDomainEvent;
use App\Register\Application\Event\RegisterConnectOnConnectedRegistered;
use App\Register\Domain\RegisterConnect;
use App\Register\Domain\RegisterConnectRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

final class RegisterConnectOnConnectedRegisteredTest extends TestCase
{
    private MockObject|RegisterConnectRepository $repository;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RegisterConnectRepository::class);
    }

    /** @test */
    public function itShouldConnectedRegistered(): void
    {
        // GIVEN

        $username1 = 'username1';
        $username2 = 'username';
        $event     = new ConnectedRegisteredDomainEvent(
            Uuid::uuid7()->toString(),
            $username1,
            $username2,
            true,
            ['organization1', 'organization2']
        );

        // WHEN

        $this->repository
            ->expects(self::once())
            ->method('save')
            ->with(
                self::callback(
                    fn (RegisterConnect $registerConnect) => $registerConnect->username1() === $username1
                        && $registerConnect->username2() === $username2
                        && $registerConnect->connected()
                        && $registerConnect->organizations() === ['organization1', 'organization2']
                        && $registerConnect->registeredAt()->format(\DateTimeInterface::ATOM) === $event->occurredOn()
                )
            );

        $eventHandler = new RegisterConnectOnConnectedRegistered(
            $this->repository
        );

        // THEN

        $eventHandler($event);
    }
}
