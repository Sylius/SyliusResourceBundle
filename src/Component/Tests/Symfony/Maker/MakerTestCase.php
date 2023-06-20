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

namespace Sylius\Component\Resource\Tests\Symfony\Maker;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class MakerTestCase extends KernelTestCase
{
    public static function removeFile(string $path): void
    {
        (new Filesystem())->remove($path);
    }

    protected static function projectDir(): string
    {
        return dirname(__DIR__, 5) . '/tests/Application';
    }

    protected static function file(string $path): string
    {
        return \sprintf('%s/%s', self::projectDir(), $path);
    }
}
