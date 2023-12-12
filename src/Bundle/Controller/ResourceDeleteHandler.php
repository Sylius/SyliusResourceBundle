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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Resource\Doctrine\Peristence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;

final class ResourceDeleteHandler implements ResourceDeleteHandlerInterface
{
    public function handle(ResourceInterface $resource, RepositoryInterface $repository): void
    {
        $repository->remove($resource);
    }
}
