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

final class ResourceMetadataCollection extends \ArrayObject
{
    private array $operationCache = [];

    public function __construct(private string $resourceClass)
    {
        parent::__construct([]);
    }

    public function getOperation(string $operationName): Operation
    {
        if (isset($this->operationCache[$operationName])) {
            return $this->operationCache[$operationName];
        }
    }
}
