<?php

declare(strict_types=1);

namespace App\Connect\User\Application\Query;

use App\Connect\User\Domain\CheckConnectionGitHub;
use App\Connect\User\Domain\ConnectedRegisteredDomainEvent;
use App\Connect\User\Domain\UserConnectionNotFoundException;
use App\Shared\Domain\Bus\Event\EventBus;
use App\Shared\Domain\Bus\Query\QueryHandler;
use App\Shared\Domain\Bus\Query\QueryResponse;
use Ramsey\Uuid\Uuid;

final class CheckGitHubUserConnectionAndOrganizationQueryHandler implements QueryHandler
{
    public function __construct(
        private readonly CheckConnectionGitHub $checkConnectionGitHub,
        private readonly EventBus $eventBus
    ) {
    }

    public function __invoke(CheckGitHubUserConnectionAndOrganizationQuery $query): QueryResponse
    {
        if (!$this->checkConnectionGitHub->checkFollowing($query->getUsername1(), $query->getUsername2())
            || !$this->checkConnectionGitHub->checkFollowing($query->getUsername2(), $query->getUsername1())) {
            throw UserConnectionNotFoundException::notConnected();
        }

        $organizations = $this->checkConnectionGitHub->getCommonOrganizations($query->getUsername1(), $query->getUsername2());

        if (empty($organizations)) {
            throw UserConnectionNotFoundException::notConnected();
        }

        $result = new CheckUsersAreConnectedAndInSameOrganizationResponse(
            connected: true,
            organizations: $organizations
        );

        $this->eventBus->publish(
            new ConnectedRegisteredDomainEvent(
                Uuid::uuid7()->toString(),
                $query->getUsername1(),
                $query->getUsername2(),
                true,
                $organizations
            )
        );

        return $result;
    }
}
