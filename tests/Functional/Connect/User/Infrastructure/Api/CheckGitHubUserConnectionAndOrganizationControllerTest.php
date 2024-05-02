<?php

declare(strict_types=1);

namespace App\Tests\Functional\Connect\User\Infrastructure\Api;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CheckGitHubUserConnectionAndOrganizationControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        $this->client = self::createClient([], ['HTTP_ACCEPT' => 'application/json']);
    }

    /** @test */
    public function itShouldCheckUsersAreConnected(): void
    {
        // GIVEN

        $username1 = 'username1';
        $username2 = 'username2';

        // WHEN

        $this->client->request('GET', "/api/connected/realtime/$username1/$username2");
        $response = $this->client->getResponse();

        // THEN

        self::assertResponseIsSuccessful();
        self::assertJson($response->getContent());
    }
}
