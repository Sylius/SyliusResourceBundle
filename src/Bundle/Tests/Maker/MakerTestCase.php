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

namespace Sylius\Bundle\ResourceBundle\Tests\Maker;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class MakerTestCase extends KernelTestCase
{
    /**
     * @before
     */
    public static function cleanupTmpDir(): void
    {
        (new Filesystem())->remove(self::tempDir());
    }

    protected static function tempDir(): string
    {
        return __DIR__ . '/../../test/tmp';
    }

    protected static function tempFile(string $path): string
    {
        return \sprintf('%s/%s', self::tempDir(), $path);
    }
}
