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

namespace Sylius\Resource\Tests\Metadata\Extractor;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Extractor\PhpFileResourceExtractor;
use Sylius\Resource\Metadata\ResourceMetadata;

final class PhpFileResourceExtractorTest extends TestCase
{
    public function testItGetsResourcesFromPhpFileThatReturnsResourceMetadata(): void
    {
        $extractor = new PhpFileResourceExtractor([__DIR__ . '/php/valid_php_file.php', __DIR__ . '/php/another_valid_php_file.php']);

        $expectedResources = [new ResourceMetadata(alias: 'dummy'), new ResourceMetadata(alias: 'another_dummy')];

        $this->assertEquals($expectedResources, $extractor->getResources());
    }

    public function testItExcludesResourcesFromPhpFileThatDoesNotReturnResourceMetadata(): void
    {
        $extractor = new PhpFileResourceExtractor([__DIR__ . '/php/invalid_php_file.php']);

        $this->assertEquals([], $extractor->getResources());
    }
}
