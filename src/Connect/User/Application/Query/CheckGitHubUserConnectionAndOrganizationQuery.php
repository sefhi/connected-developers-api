<?php

declare(strict_types=1);

namespace App\Connect\User\Application\Query;

use App\Shared\Domain\Bus\Query\Query;

final readonly class CheckGitHubUserConnectionAndOrganizationQuery implements Query
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
}
