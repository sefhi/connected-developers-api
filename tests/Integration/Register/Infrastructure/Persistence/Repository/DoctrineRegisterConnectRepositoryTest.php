<?php

declare(strict_types=1);

namespace App\Tests\Integration\Register\Infrastructure\Persistence\Repository;

use App\Register\Infrastructure\Persistence\Repository\DoctrineRegisterConnectRepository;
use App\Shared\Domain\Criteria\Criteria;
use App\Shared\Domain\Criteria\Filter;
use App\Shared\Domain\Criteria\FilterField;
use App\Shared\Domain\Criteria\FilterOperator;
use App\Shared\Domain\Criteria\Filters;
use App\Shared\Domain\Criteria\FilterValue;
use App\Shared\Domain\Criteria\Order;
use App\Shared\Domain\Criteria\OrderBy;
use App\Shared\Domain\Criteria\OrderType;
use App\Shared\Domain\Criteria\OrderTypes;
use App\Tests\Unit\Register\Domain\RegisterConnectMother;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class DoctrineRegisterConnectRepositoryTest extends KernelTestCase
{
    private DoctrineRegisterConnectRepository $albumRepository;

    public function setUp(): void
    {
        self::bootKernel();
        /* @var DoctrineRegisterConnectRepository albumRepository */
        $this->albumRepository = new DoctrineRegisterConnectRepository(
            self::getContainer()->get('doctrine.orm.entity_manager')
        );
    }

    /** @test */
    public function itShouldSaveRegisterConnect(): void
    {
        // GIVEN

        $registerConnect = RegisterConnectMother::random();

        // WHEN

        $this->albumRepository->save($registerConnect);

        // THEN
        self::assertSame(
            $registerConnect,
            $this->albumRepository->search(
                Criteria::create(
                    Filters::fromArray(
                        [
                            Filter::create(
                                FilterField::fromString('username1'),
                                FilterOperator::EQUAL,
                                FilterValue::fromString($registerConnect->username1()),
                            ),
                            Filter::create(
                                FilterField::fromString('username2'),
                                FilterOperator::EQUAL,
                                FilterValue::fromString($registerConnect->username2()),
                            ),
                        ],
                    ),
                    Order::create(
                        OrderBy::fromString('registeredAt'),
                        OrderType::fromString(OrderTypes::ASC->value)
                    )
                )
            )
        );
    }
}
