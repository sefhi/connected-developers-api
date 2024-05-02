<?php

declare(strict_types=1);

namespace App\Connect\User\Infrastructure\GitHub;

use App\Connect\User\Domain\CheckConnectionGitHub;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class SymfonyHttpCheckConnectionGitHub implements CheckConnectionGitHub
{
    public function __construct(private HttpClientInterface $httpClient)
    {
    }

    public function checkFollowing(string $usernameCurrent, string $usernameTarget): bool
    {
        $response = $this->httpClient->request('GET', "https://api.github.com/users/$usernameCurrent/following/$usernameTarget");

        return 204 === $response->getStatusCode();
    }

    public function getCommonOrganizations(string $username1, string $username2): array
    {
        $responseOrganizationUsername1 = $this->httpClient->request('GET', "https://api.github.com/users/$username1/orgs");

        if (Response::HTTP_OK !== $responseOrganizationUsername1->getStatusCode() || empty($responseOrganizationUsername1->toArray())) {
            return [];
        }

        $responseOrganizationUsername2 = $this->httpClient->request('GET', "https://api.github.com/users/$username2/orgs");

        if (Response::HTTP_OK !== $responseOrganizationUsername2->getStatusCode() || empty($responseOrganizationUsername2->toArray())) {
            return [];
        }

        $organizationUsername1 = $this->cleanOrganization($responseOrganizationUsername1->toArray());
        $organizationUsername2 = $this->cleanOrganization($responseOrganizationUsername1->toArray());

        return array_intersect($organizationUsername1, $organizationUsername2);
    }

    private function cleanOrganization(array $organizations): array
    {
        return array_map(fn (array $organization) => $organization['login'], $organizations);
    }
}
