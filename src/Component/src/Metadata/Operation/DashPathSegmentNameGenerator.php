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

namespace Sylius\Resource\Metadata\Operation;

use Sylius\Resource\Metadata\Inflector\InflectorInterface;

/**
 * Generate a path name with a dash separator according to a string and whether it's a collection or not.
 */
final class DashPathSegmentNameGenerator implements PathSegmentNameGeneratorInterface
{
    public function __construct(
        private readonly InflectorInterface $inflector,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getSegmentName(string $name, bool $pluralize = true): string
    {
        return $pluralize ? $this->inflector->dashize($this->inflector->pluralize($name)) : $this->inflector->dashize($name);
    }
}
