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

namespace Sylius\Resource\Factory;

/**
 * Creates resources based on theirs FQCN.
 */
final class Factory implements FactoryInterface
{
    /**
     * @var class-string
     */
    private $className;

    /**
     * @param class-string $className
     */
    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function createNew()
    {
        return new $this->className();
    }
}

class_alias(Factory::class, \Sylius\Component\Resource\Factory\Factory::class);
