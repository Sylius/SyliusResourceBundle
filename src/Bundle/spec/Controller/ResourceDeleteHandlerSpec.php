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

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class ResourceDeleteHandlerSpec extends ObjectBehavior
{
    function it_implements_a_resource_delete_handler_interface(): void
    {
        $this->shouldImplement(ResourceDeleteHandlerInterface::class);
    }

    function it_removes_resource_via_repository(RepositoryInterface $repository, ResourceInterface $resource): void
    {
        $repository->remove($resource)->shouldBeCalled();

        $this->handle($resource, $repository);
    }
}
