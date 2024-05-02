<?php

declare(strict_types=1);

namespace App\Connect\User\Application\Query;

use App\Connect\User\Domain\CheckConnectionGitHub;
use App\Connect\User\Domain\UserConnectionNotFoundException;
use App\Shared\Domain\Bus\Query\QueryResponse;

final class CheckGitHubUserConnectionAndOrganizationQueryHandler
{
    public function __construct(
        private readonly CheckConnectionGitHub $checkConnectionGitHub
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

        return new CheckUsersAreConnectedAndInSameOrganizationResponse(
            connected: true,
            organizations: $organizations
        );
    }
}
