<?php

declare(strict_types=1);

namespace App\Tests\Unit\Connect\User\Application\Query;

use App\Connect\User\Application\Query\CheckGitHubUserConnectionAndOrganizationQuery;
use App\Connect\User\Application\Query\CheckGitHubUserConnectionAndOrganizationQueryHandler;
use App\Connect\User\Domain\CheckConnectionGitHub;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class CheckGitHubUserConnectionAndOrganizationQueryHandlerTest extends TestCase
{
    private CheckConnectionGitHub|MockObject $checkConnectionGitHub;

    protected function setUp(): void
    {
        $this->checkConnectionGitHub = $this->createMock(CheckConnectionGitHub::class);
    }

    /** @test  */
    public function itShouldCheckUsersAreConnectedAndInSameOrganization(): void
    {
        // GIVEN
        $username1 = 'username1';
        $username2 = 'username2';
        $query     = new CheckGitHubUserConnectionAndOrganizationQuery(
            $username1,
            $username2
        );

        // WHEN

        $this->checkConnectionGitHub
            ->expects(self::exactly(2))
            ->method('checkFollowing')
            ->willReturnCallback(
                function (string $usernameCurrent, string $usernameTarget) use ($username1, $username2) {
                    return $usernameCurrent === $username1 && $usernameTarget === $username2 || $usernameCurrent === $username2 && $usernameTarget === $username1;
                }
            );

        $handler = new CheckGitHubUserConnectionAndOrganizationQueryHandler(
            $this->checkConnectionGitHub
        );

        // THEN

        self::assertTrue($handler($query));
    }
}
