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

namespace Sylius\Component\Resource\ClassFinder;

use Iterator;
use ReflectionClass;

interface RecursiveClassFinderInterface
{
    /**
     * @param array $directories
     * @return Iterator<string, ReflectionClass>
     */
    public function findInDirectories(array $directories): Iterator;

    /**
     * @param array $directories
     * @param string $attribute
     * @return Iterator<string, ReflectionClass>
     */
    public function findInDirectoriesWithAttribute(array $directories, string $attribute): Iterator;
}
