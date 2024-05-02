<?php

declare(strict_types=1);

namespace App\Tests\Unit\Connect\User\Infrastructure\GitHub;

use App\Connect\User\Infrastructure\GitHub\SymfonyHttpCheckConnectionGitHub;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

final class SymfonyHttpCheckConnectionGitHubTest extends TestCase
{
    private MockHttpClient $httpClient;

    protected function setUp(): void
    {
        $this->httpClient = new MockHttpClient();
    }

    /** @test */
    public function itShouldCheckUsersAreConnected(): void
    {
        // GIVEN

        $username1        = 'username1';
        $username2        = 'username2';
        $responseExpected = new MockResponse('', ['http_code' => 204]);
        $this->httpClient = new MockHttpClient($responseExpected, 'https://api.github.com/');

        // WHEN

        $checkConnection = new SymfonyHttpCheckConnectionGitHub($this->httpClient);
        $isConnected     = $checkConnection->checkFollowing($username1, $username2);

        // THEN

        self::assertSame('GET', $responseExpected->getRequestMethod());
        self::assertSame("https://api.github.com/users/$username1/following/$username2", $responseExpected->getRequestUrl());
        self::assertTrue($isConnected);
    }

    /** @test */
    public function itShouldCheckUsersAreSameOrganization(): void
    {
        // GIVEN

        $username1 = 'username1';
        $username2 = 'username2';

        $responseExpected1 = new MockResponse($this->mockOrganizationResponseJson(), ['http_code' => 200]);
        $responseExpected2 = new MockResponse($this->mockOrganizationResponseJson(), ['http_code' => 200]);

        $expectedRequests = [
            function ($method, $url, $options) use ($username1, $responseExpected1): MockResponse {
                $this->assertSame('GET', $method);
                $this->assertSame("https://api.github.com/users/$username1/orgs", $url);

                return $responseExpected1;
            },
            function ($method, $url, $options) use ($username2, $responseExpected2): MockResponse {
                $this->assertSame('GET', $method);
                $this->assertSame("https://api.github.com/users/$username2/orgs", $url);

                return $responseExpected2;
            },
        ];

        $this->httpClient = new MockHttpClient($expectedRequests, 'https://api.github.com/');

        // WHEN

        $checkConnection = new SymfonyHttpCheckConnectionGitHub($this->httpClient);
        $result          = $checkConnection->getCommonOrganizations($username1, $username2);

        // THEN

        self::assertSame(['symfony', 'doctrine'], $result);
    }

    /** @test */
    public function itShouldReturnEmptyResultWhenUser1NoneSameOrganization(): void
    {
        // GIVEN

        $username1 = 'username1';
        $username2 = 'username2';

        $responseExpected1 = new MockResponse('[]', ['http_code' => 200]);

        $expectedRequests = [
            function ($method, $url, $options) use ($username1, $responseExpected1): MockResponse {
                $this->assertSame('GET', $method);
                $this->assertSame("https://api.github.com/users/$username1/orgs", $url);

                return $responseExpected1;
            },
        ];

        $this->httpClient = new MockHttpClient($expectedRequests, 'https://api.github.com/');

        // WHEN

        $checkConnection = new SymfonyHttpCheckConnectionGitHub($this->httpClient);
        $result          = $checkConnection->getCommonOrganizations($username1, $username2);

        // THEN

        self::assertTrue(empty($result));
    }

    /** @test */
    public function itShouldReturnEmptyResultWhenUser2NoneSameOrganization(): void
    {
        // GIVEN

        $username1 = 'username1';
        $username2 = 'username2';

        $responseExpected1 = new MockResponse($this->mockOrganizationResponseJson(), ['http_code' => 200]);
        $responseExpected2 = new MockResponse('[]', ['http_code' => 200]);

        $expectedRequests = [
            function ($method, $url, $options) use ($username1, $responseExpected1): MockResponse {
                $this->assertSame('GET', $method);
                $this->assertSame("https://api.github.com/users/$username1/orgs", $url);

                return $responseExpected1;
            },
            function ($method, $url, $options) use ($username2, $responseExpected2): MockResponse {
                $this->assertSame('GET', $method);
                $this->assertSame("https://api.github.com/users/$username2/orgs", $url);

                return $responseExpected2;
            },
        ];

        $this->httpClient = new MockHttpClient($expectedRequests, 'https://api.github.com/');

        // WHEN

        $checkConnection = new SymfonyHttpCheckConnectionGitHub($this->httpClient);
        $result          = $checkConnection->getCommonOrganizations($username1, $username2);

        // THEN

        self::assertTrue(empty($result));
    }

    private function mockOrganizationResponseJson(): string
    {
        return <<<JSON
                    [
                      {
                        "login": "symfony",
                        "id": 143937,
                        "node_id": "MDEyOk9yZ2FuaXphdGlvbjE0MzkzNw==",
                        "url": "https://api.github.com/orgs/symfony",
                        "repos_url": "https://api.github.com/orgs/symfony/repos",
                        "events_url": "https://api.github.com/orgs/symfony/events",
                        "hooks_url": "https://api.github.com/orgs/symfony/hooks",
                        "issues_url": "https://api.github.com/orgs/symfony/issues",
                        "members_url": "https://api.github.com/orgs/symfony/members{/member}",
                        "public_members_url": "https://api.github.com/orgs/symfony/public_members{/member}",
                        "avatar_url": "https://avatars.githubusercontent.com/u/143937?v=4",
                        "description": ""
                      },
                      {
                        "login": "doctrine",
                        "id": 209254,
                        "node_id": "MDEyOk9yZ2FuaXphdGlvbjIwOTI1NA==",
                        "url": "https://api.github.com/orgs/doctrine",
                        "repos_url": "https://api.github.com/orgs/doctrine/repos",
                        "events_url": "https://api.github.com/orgs/doctrine/events",
                        "hooks_url": "https://api.github.com/orgs/doctrine/hooks",
                        "issues_url": "https://api.github.com/orgs/doctrine/issues",
                        "members_url": "https://api.github.com/orgs/doctrine/members{/member}",
                        "public_members_url": "https://api.github.com/orgs/doctrine/public_members{/member}",
                        "avatar_url": "https://avatars.githubusercontent.com/u/209254?v=4",
                        "description": "The Doctrine Project is the home to several PHP libraries primarily focused on database storage and object mapping."
                      }
                    ]
                    JSON;
    }
}
