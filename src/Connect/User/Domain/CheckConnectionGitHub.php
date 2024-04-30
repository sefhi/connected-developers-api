<?php

declare(strict_types=1);

namespace App\Connect\User\Domain;

interface CheckConnectionGitHub
{
    public function checkFollowing(string $usernameCurrent, string $usernameTarget): bool;

    public function getCommonOrganizations(string $username1, string $username2): array;
}
