<?php

declare(strict_types=1);

namespace App\Connect\User\Infrastructure\Api\Dto;

use App\Connect\User\Application\Query\CheckGitHubUserConnectionAndOrganizationQuery;

final readonly class CheckGitHubDto
{
    public function __construct(
        private string $username1,
        private string $username2
    ) {
    }

    public function getUsername1(): string
    {
        return $this->username1;
    }

    public function getUsername2(): string
    {
        return $this->username2;
    }

    public function mapToQuery(): CheckGitHubUserConnectionAndOrganizationQuery
    {
        return new CheckGitHubUserConnectionAndOrganizationQuery(
            username1: $this->username1,
            username2: $this->username2
        );
    }
}
