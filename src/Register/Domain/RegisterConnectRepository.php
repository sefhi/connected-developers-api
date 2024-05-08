<?php

declare(strict_types=1);

namespace App\Register\Domain;

interface RegisterConnectRepository
{
    public function save(RegisterConnect $registerConnect): void;
}
