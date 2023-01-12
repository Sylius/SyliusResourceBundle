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

namespace Sylius\Component\Resource\Metadata\Resource;

use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;

final class ResourceMetadataCollection extends \ArrayObject
{
    public function getOperation(string $resourceAlias, string $name, ?string $section = null): Operation
    {
        /** @var ResourceMetadata $current */
        foreach ($this->getIterator() as $current) {
            if (
                $current->getAlias() === $resourceAlias &&
                $current->getSection() === $section &&
                $current->hasOperation($name)
            ) {
                return $current->getOperation($name);
            }
        }

        if (null === $section)  {
            throw new \RuntimeException(sprintf(
                'Operation "%s" for "%s" resource was not found.',
                $resourceAlias,
                $name,
            ));
        }

        throw new \RuntimeException(sprintf(
            'Operation "%s" for "%s" resource with section "%s" was not found.',
            $resourceAlias,
            $name,
            $section,
        ));
    }
}
