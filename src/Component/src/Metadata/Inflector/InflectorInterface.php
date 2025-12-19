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

namespace Sylius\Resource\Metadata\Inflector;

interface InflectorInterface
{
    public function tableize(string $string): string;

    public function pluralize(string $string): string;

    public function dashize(string $string): string;
}
