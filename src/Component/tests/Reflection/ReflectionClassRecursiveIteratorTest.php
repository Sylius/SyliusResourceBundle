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

namespace Sylius\Resource\Tests\Reflection;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Reflection\ReflectionClassRecursiveIterator;

final class ReflectionClassRecursiveIteratorTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        parent::setUp();

        // Create a temporary directory for test PHP files
        $this->tmpDir = sys_get_temp_dir() . '/reflection_iterator_' . uniqid('', true);
        mkdir($this->tmpDir, 0777, true);
    }

    protected function tearDown(): void
    {
        $this->removeDirectory($this->tmpDir);

        parent::tearDown();
    }

    public function testItReturnsReflectionClassesFromGivenDirectories(): void
    {
        $this->createPhpFile(
            $this->tmpDir . '/Foo.php',
            <<<'PHP'
            <?php

            namespace Test\Fixtures;

            final class Foo
            {
            }
            PHP
        );

        $this->createPhpFile(
            $this->tmpDir . '/Bar.php',
            <<<'PHP'
            <?php

            namespace Test\Fixtures;

            interface Bar
            {
            }
            PHP
        );

        $reflections = ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories([
            $this->tmpDir,
        ]);

        self::assertArrayHasKey('Test\\Fixtures\\Foo', $reflections);
        self::assertArrayHasKey('Test\\Fixtures\\Bar', $reflections);

        self::assertInstanceOf(\ReflectionClass::class, $reflections['Test\\Fixtures\\Foo']);
        self::assertInstanceOf(\ReflectionClass::class, $reflections['Test\\Fixtures\\Bar']);

        self::assertSame(
            realpath($this->tmpDir . '/Foo.php'),
            $reflections['Test\\Fixtures\\Foo']->getFileName(),
        );
    }

    public function testItUsesLocalCacheForSameDirectories(): void
    {
        $this->createPhpFile(
            $this->tmpDir . '/Cached.php',
            <<<'PHP'
            <?php

            namespace Test\Fixtures;

            final class Cached
            {
            }
            PHP
        );

        $firstCall = ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories([
            $this->tmpDir,
        ]);

        $secondCall = ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories([
            $this->tmpDir,
        ]);

        // Same array instance returned from cache
        self::assertSame($firstCall, $secondCall);
        self::assertArrayHasKey('Test\\Fixtures\\Cached', $secondCall);
    }

    public function testItIgnoresInvalidPhpFiles(): void
    {
        $this->createPhpFile(
            $this->tmpDir . '/Invalid.php',
            <<<'PHP'
            <?php

            // This file is intentionally invalid
            class Invalid extends UnknownParent
            {
            }
            PHP
        );

        $reflections = ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories([
            $this->tmpDir,
        ]);

        self::assertSame([], $reflections);
    }

    public function testItOnlyIncludesFilesMatchingIgnoreRegex(): void
    {
        $this->createPhpFile(
            $this->tmpDir . '/IncludedOne.php',
            <<<'PHP'
        <?php

        namespace Test\Fixtures;

        final class IncludedOne
        {
        }
        PHP
        );

        $this->createPhpFile(
            $this->tmpDir . '/Excluded.php',
            <<<'PHP'
        <?php

        namespace Test\Fixtures;

        final class Excluded
        {
        }
        PHP
        );

        $reflections = ReflectionClassRecursiveIterator::getReflectionClassesFromDirectories(
            [$this->tmpDir],
            '.*Included',
        );

        self::assertArrayHasKey('Test\\Fixtures\\IncludedOne', $reflections);
        self::assertArrayNotHasKey('Test\\Fixtures\\Excluded', $reflections);
    }

    private function createPhpFile(string $path, string $contents): void
    {
        file_put_contents($path, $contents);
    }

    private function removeDirectory(string $directory): void
    {
        if (!is_dir($directory)) {
            return;
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST,
        );

        foreach ($files as $file) {
            $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
        }

        rmdir($directory);
    }
}
