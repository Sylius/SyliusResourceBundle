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

namespace Sylius\Resource\Metadata\Extractor;

use Sylius\Resource\Exception\InvalidArgumentException;

/**
 * Extracts an array of metadata from a file or a list of files.
 */
interface ResourceExtractorInterface
{
    /**
     * Parses all metadata files and convert them in an array.
     *
     * @throws InvalidArgumentException
     */
    public function getResources(): array;
}
