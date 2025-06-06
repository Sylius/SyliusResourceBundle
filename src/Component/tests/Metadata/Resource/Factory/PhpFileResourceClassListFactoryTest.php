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

namespace Sylius\Resource\Tests\Metadata\Resource\Factory;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\Extractor\PhpFileResourceExtractor;
use Sylius\Resource\Metadata\Extractor\ResourceExtractorInterface;
use Sylius\Resource\Metadata\Resource\Factory\PhpFileResourceClassListFactory;

final class PhpFileResourceClassListFactoryTest extends TestCase
{
    public function testCreateAResourceClassListForPhpConfigurationFiles(): void
    {
        $attributesResourceClassListFactory = new PhpFileResourceClassListFactory(
            $this->getExtractor(),
        );

        $list = $attributesResourceClassListFactory->create();

        $this->assertCount(1, $list->getIterator());
        $this->assertContains(\stdClass::class, $list->getIterator());
    }

    private function getExtractor(): ResourceExtractorInterface
    {
        return new PhpFileResourceExtractor([
            __DIR__ . '/php/php_file_with_resource_class.php',
            __DIR__ . '/php/php_file_without_resource_class.php',
        ]);
    }
}
