<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Sylius\Resource\Tests\Tmp\Sylius\Resource;

use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Uid\AbstractUid;

#[Resource]
#[Index(
    // grid: Tests\Tmp\Sylius\Resource\BookGrid::class,
)]
#[Create(
    // processor: CreateTests\Tmp\Sylius\Resource\BookProcessor::class,
)]
#[Update(
    // provider: Tests\Tmp\Sylius\Resource\BookItemProvider::class,
    // processor: UpdateTests\Tmp\Sylius\Resource\BookProcessor::class,
)]
#[Delete(
    // provider: Tests\Tmp\Sylius\Resource\BookItemProvider::class,
    // processor: DeleteTests\Tmp\Sylius\Resource\BookProcessor::class,
)]
#[Show(
    // template: 'tests\tmp\sylius\resource\book/show.html.twig',
    // provider: Tests\Tmp\Sylius\Resource\BookItemProvider::class,
)]
final class BookResource implements ResourceInterface
{
    public function __construct(
        public ?AbstractUid $id = null,
    ) {
    }

    public function getId(): ?string
    {
        return $this->id?->__toString();
    }
}
