<?php

declare(strict_types=1);

namespace App\Connect\User\Domain;

use App\Shared\Domain\Bus\Event\DomainEvent;

final class ConnectedRegisteredDomainEvent extends DomainEvent
{
    public function __construct(
        private readonly string $aggregateId,
        private readonly string $username1,
        private readonly string $username2,
        private readonly bool $connected,
        private readonly array $organizations,
        ?string $eventId = null,
        ?string $occurredOn = null
    ) {
        parent::__construct(
            $aggregateId,
            $eventId,
            $occurredOn
        );
    }

    public static function fromPrimitives(
        string $aggregateId,
        array $body,
        ?string $eventId = null,
        ?string $occurredOn = null,
    ): self {
        return new self(
            $aggregateId,
            $body['username1'],
            $body['username2'],
            $body['connected'],
            $body['organizationsExpected'],
            $eventId,
            $occurredOn
        );
    }

    public static function eventName(): string
    {
        return 'connected_developers_api.connect.registered';
    }

    public function toPrimitives(): array
    {
        return [
            'id'                    => $this->aggregateId,
            'username1'             => $this->username1,
            'username2'             => $this->username2,
            'connected'             => $this->connected,
            'organizationsExpected' => $this->organizations,
        ];
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getUsername1(): string
    {
        return $this->username1;
    }

    public function getUsername2(): string
    {
        return $this->username2;
    }

    public function isConnected(): bool
    {
        return $this->connected;
    }

    public function getOrganizations(): array
    {
        return $this->organizations;
    }
}
