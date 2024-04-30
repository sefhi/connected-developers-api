<?php

declare(strict_types=1);

namespace App\Connect\User\Application\Query;

use App\Shared\Domain\Bus\Query\QueryResponse;

final readonly class CheckUsersAreConnectedAndInSameOrganizationResponse implements QueryResponse, \JsonSerializable
{
    public function __construct(
        private bool $connected,
        private array $organizations,
    ) {
    }

    public function connected(): bool
    {
        return $this->connected;
    }

    public function organizations(): array
    {
        return $this->organizations;
    }

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
