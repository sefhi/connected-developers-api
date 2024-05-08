<?php

declare(strict_types=1);

namespace App\Register\Domain;

use App\Shared\Domain\Criteria\Criteria;

interface RegisterConnectRepository
{
    public function save(RegisterConnect $registerConnect): void;

    public function search(Criteria $criteria): array;

    public function searchOneBy(Criteria $criteria): ?RegisterConnect;
}
