<?php

declare(strict_types=1);

namespace App\Register\Application\Event;

use App\Connect\User\Domain\ConnectedRegisteredDomainEvent;
use App\Register\Domain\RegisterConnect;
use App\Register\Domain\RegisterConnectRepository;
use App\Shared\Domain\Bus\Event\EventHandler;
use Ramsey\Uuid\Uuid;

final readonly class RegisterConnectOnConnectedRegistered implements EventHandler
{
    public function __construct(
        private RegisterConnectRepository $repository
    ) {
    }

    public function __invoke(ConnectedRegisteredDomainEvent $event): void
    {
        $registerConnect = RegisterConnect::create(
            Uuid::uuid7(),
            $event->getUsername1(),
            $event->getUsername2(),
            $event->isConnected(),
            $event->getOrganizations(),
            new \DateTimeImmutable($event->occurredOn())
        );

        $this->repository->save($registerConnect);
    }
}
