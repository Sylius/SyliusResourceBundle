<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Tests\Repository;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Repository\InMemoryRepository;
use Sylius\Component\Resource\Tests\Fixtures\SampleBookResourceInterface;
use Sylius\Resource\Doctrine\Persistence\InMemoryRepository as NewInMemoryRepository;

require_once dirname(__DIR__) . '/Fixtures/SampleBookResourceInterface.php';

final class InMemoryRepositoryTest extends TestCase
{
    private InMemoryRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new InMemoryRepository(SampleBookResourceInterface::class);
    }

    public function testItShouldBeAnAliasOfInMemoryRepository(): void
    {
        $this->assertInstanceOf(NewInMemoryRepository::class, $this->repository);
    }
}
