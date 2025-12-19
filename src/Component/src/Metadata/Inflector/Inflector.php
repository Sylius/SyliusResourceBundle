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

use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\UnicodeString;

final class Inflector implements InflectorInterface
{
    public function tableize(string $string): string
    {
        return (new UnicodeString($string))->snake()->toString();
    }

    public function pluralize(string $string): string
    {
        $pluralize = (new EnglishInflector())->pluralize($string);

        return $pluralize ? array_pop($pluralize) : '';
    }

    public function dashize(string $string): string
    {
        return strtr($this->tableize($string), ['_' => '-']);
    }
}
