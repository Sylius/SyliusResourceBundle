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

namespace spec\Sylius\Component\Resource\Repository;

use PhpSpec\ObjectBehavior;
use spec\Sylius\Component\Resource\Fixtures\SampleBookResourceInterface;
use Sylius\Resource\Doctrine\Persistence\InMemoryRepository as NewInMemoryRepository;

require_once dirname(__DIR__) . '/Fixtures/SampleBookResourceInterface.php';

final class InMemoryRepositorySpec extends ObjectBehavior
{
    function let(): void
    {
        $this->beConstructedWith(SampleBookResourceInterface::class);
    }

    function it_should_be_an_alias_of_in_memory_repository(): void
    {
        $this->shouldImplement(NewInMemoryRepository::class);
    }
}
