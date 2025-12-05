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

use Sylius\Resource\Metadata\Inflector\Inflector;
use Sylius\Resource\Metadata\Inflector\InflectorInterface;

/**
 * Generate a path name with an underscore separator according to a string and whether it's a collection or not.
 */
final class UnderscorePathSegmentNameGenerator implements PathSegmentNameGeneratorInterface
{
    private readonly InflectorInterface $inflector;

    public function __construct(
        ?InflectorInterface $inflector = null,
    ) {
        $this->inflector = $inflector ?? new Inflector();
    }

    /**
     * @inheritdoc
     */
    public function getSegmentName(string $name, bool $pluralize = true): string
    {
        $name = $this->inflector->tableize($name);

        return $pluralize ? $this->inflector->pluralize($name) : $name;
    }
}
