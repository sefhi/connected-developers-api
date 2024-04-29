<?php

declare(strict_types=1);

namespace App\Connect\User\Application\Query;

use App\Connect\User\Domain\CheckConnectionGitHub;

final class CheckGitHubUserConnectionAndOrganizationQueryHandler
{
    public function __construct(
        private readonly CheckConnectionGitHub $checkConnectionGitHub
    ) {
    }

    public function __invoke(CheckGitHubUserConnectionAndOrganizationQuery $query): bool
    {
        return $this->checkConnectionGitHub->checkFollowing($query->getUsername1(), $query->getUsername2())
        && $this->checkConnectionGitHub->checkFollowing($query->getUsername2(), $query->getUsername1());
    }
}
