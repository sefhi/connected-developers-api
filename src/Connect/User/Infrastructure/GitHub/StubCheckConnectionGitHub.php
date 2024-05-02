<?php

declare(strict_types=1);

namespace App\Connect\User\Infrastructure\GitHub;

readonly class StubCheckConnectionGitHub extends SymfonyHttpCheckConnectionGitHub
{
    public function checkFollowing(string $usernameCurrent, string $usernameTarget): bool
    {
        return true;
    }

    public function getCommonOrganizations(string $username1, string $username2): array
    {
        return ['organization1'];
    }
}
