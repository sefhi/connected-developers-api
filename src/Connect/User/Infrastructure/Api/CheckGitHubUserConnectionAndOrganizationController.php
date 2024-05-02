<?php

declare(strict_types=1);

namespace App\Connect\User\Infrastructure\Api;

use App\Connect\User\Infrastructure\Api\Dto\CheckGitHubDto;
use App\Shared\Api\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class CheckGitHubUserConnectionAndOrganizationController extends BaseController
{
    public function __invoke(string $username1, string $username2): JsonResponse
    {
        $dto = new CheckGitHubDto($username1, $username2);

        $result = $this->query($dto->mapToQuery());

        return new JsonResponse($result, Response::HTTP_OK);
    }

    protected function exceptions(): array
    {
        return [];
    }
}
