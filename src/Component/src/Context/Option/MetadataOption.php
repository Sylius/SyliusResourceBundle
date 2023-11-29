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

namespace Sylius\Resource\Context\Option;

use Sylius\Resource\Metadata\MetadataInterface;

final class MetadataOption
{
    public function __construct(private MetadataInterface $metadata)
    {
    }

    public function metadata(): MetadataInterface
    {
        return $this->metadata;
    }
}
