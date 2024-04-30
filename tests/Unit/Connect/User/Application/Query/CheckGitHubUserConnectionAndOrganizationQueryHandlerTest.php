<?php

declare(strict_types=1);

namespace App\Tests\Unit\Connect\User\Application\Query;

use App\Connect\User\Application\Query\CheckGitHubUserConnectionAndOrganizationQuery;
use App\Connect\User\Application\Query\CheckGitHubUserConnectionAndOrganizationQueryHandler;
use App\Connect\User\Application\Query\CheckUsersAreConnectedAndInSameOrganizationResponse;
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
        $organizationsExpected = ['organization1', 'organization2'];

        // WHEN

        $this->checkConnectionGitHub
            ->expects(self::exactly(2))
            ->method('checkFollowing')
            ->willReturnOnConsecutiveCalls(true, true);

        $this->checkConnectionGitHub
            ->expects(self::once())
            ->method('getCommonOrganizations')
            ->with($username1, $username2)
            ->willReturn($organizationsExpected);

        $handler = new CheckGitHubUserConnectionAndOrganizationQueryHandler(
            $this->checkConnectionGitHub
        );

        // THEN

        $result = $handler($query);

        self::assertInstanceOf(CheckUsersAreConnectedAndInSameOrganizationResponse::class, $result);
        self::assertTrue($result->connected());
        self::assertSame($organizationsExpected, $result->organizations());
    }

    /** @test  */
    public function itShouldThrownErrorWhenNotFollowingAtLeastOneUser(): void
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
            ->expects(self::once())
            ->method('checkFollowing')
            ->willReturn(false);

        $this->checkConnectionGitHub
            ->expects(self::never())
            ->method('getCommonOrganizations');

        $handler = new CheckGitHubUserConnectionAndOrganizationQueryHandler(
            $this->checkConnectionGitHub
        );

        // THEN

        $result = $handler($query);

        self::assertInstanceOf(CheckUsersAreConnectedAndInSameOrganizationResponse::class, $result);
        self::assertFalse($result->connected());
        self::assertEmpty($result->organizations());
    }
}
