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

namespace Reflection;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Sylius\Component\Resource\Tests\Dummy\DummyClassOne;
use Sylius\Component\Resource\Tests\Dummy\DummyClassTwo;
use Sylius\Component\Resource\Tests\Dummy\TraitPass;

final class ClassReflectionTest extends TestCase
{
    /** @test */
    public function it_returns_resource_classes_from_a_directory(): void
    {
        $resources = ClassReflection::getResourcesByPath(dirname(__FILE__).'/../Dummy');

        $this->assertSame($resources, [
            DummyClassOne::class,
            DummyClassTwo::class,
        ]);
    }

    /** @test */
    public function it_excludes_traits(): void
    {
        $resources = ClassReflection::getResourcesByPath(dirname(__FILE__).'/../Dummy');

        $this->assertNotContains(TraitPass::class, $resources);
    }
}
