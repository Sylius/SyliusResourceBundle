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

namespace Sylius\Component\Resource\Tests\Dummy;

final class RepositoryWithCallables
{
    public function __invoke(string $id): \stdClass
    {
        return self::find($id);
    }

    public static function find(string $id): \stdClass
    {
        $stdClass = new \stdClass();
        $stdClass->id = $id;

        return $stdClass;
    }

    public static function findOneBy(array $criteria, ?array $orderBy = null): array
    {
        return [];
    }

    public function __call(string $method, mixed $arguments): array
    {
        return [];
    }
}
