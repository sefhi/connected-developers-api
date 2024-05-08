<?php

declare(strict_types=1);

namespace App\Register\Infrastructure\Persistence\Repository;

use App\Register\Domain\RegisterConnect;
use App\Register\Domain\RegisterConnectRepository;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Infrastructure\Persistence\Criteria\DoctrineCriteriaConverter;
use App\Shared\Infrastructure\Persistence\Repository\DoctrineRepository;

final class DoctrineRegisterConnectRepository extends DoctrineRepository implements RegisterConnectRepository
{
    private static array $criteriaToDoctrineFields = [
        'id'            => 'id',
        'username1'     => 'username1',
        'username2'     => 'username2',
        'connected'     => 'connected',
        'organizations' => 'organizations',
        'registeredAt'  => 'registeredAt',
    ];

    private static array $hydrators = [
        'registeredAt' => \DateTimeImmutable::class,
    ];

    public function save(RegisterConnect $registerConnect): void
    {
        $this->persist($registerConnect);
    }

    public function search(Criteria $criteria): array
    {
        return [];
    }

    public function searchOneBy(Criteria $criteria): ?RegisterConnect
    {
        $registerRepository = $this->repository(RegisterConnect::class);

        $registerConnect = $registerRepository->matching(
            DoctrineCriteriaConverter::convert($criteria, self::$criteriaToDoctrineFields, self::$hydrators)
        )->first();

        return $registerConnect instanceof RegisterConnect ? $registerConnect : null;
    }
}
