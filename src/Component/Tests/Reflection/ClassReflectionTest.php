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

use App\Entity\CrudRoutes\BookWithCriteria;
use App\Entity\Route\ShowBook;
use App\Entity\Route\ShowBookWithPriority;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Annotation\SyliusCrudRoutes;
use Sylius\Component\Resource\Annotation\SyliusRoute;
use Sylius\Component\Resource\Reflection\ClassReflection;
use Sylius\Component\Resource\Tests\Dummy\DummyClassOne;
use Sylius\Component\Resource\Tests\Dummy\DummyClassTwo;
use Sylius\Component\Resource\Tests\Dummy\TraitPass;

final class ClassReflectionTest extends TestCase
{
    /** @test */
    public function it_returns_resource_classes_from_paths(): void
    {
        $resources = ClassReflection::getResourcesByPaths([__DIR__ . '/../Dummy']);

        $this->assertContains(DummyClassOne::class, $resources);
        $this->assertContains(DummyClassTwo::class, $resources);
    }

    /** @test */
    public function it_returns_resource_classes_from_a_directory(): void
    {
        $resources = ClassReflection::getResourcesByPath(__DIR__ . '/../Dummy');

        $this->assertContains(DummyClassOne::class, $resources);
        $this->assertContains(DummyClassTwo::class, $resources);
    }

    /** @test */
    public function it_excludes_traits(): void
    {
        $resources = ClassReflection::getResourcesByPath(__DIR__ . '/../Dummy');

        $this->assertNotContains(TraitPass::class, $resources);
    }

    /** @test */
    public function it_returns_class_attributes(): void
    {
        $this->assertCount(1, ClassReflection::getClassAttributes(BookWithCriteria::class, SyliusCrudRoutes::class));
        $this->assertCount(1, ClassReflection::getClassAttributes(ShowBook::class, SyliusRoute::class));
        $this->assertCount(2, ClassReflection::getClassAttributes(ShowBookWithPriority::class, SyliusRoute::class));
    }
}
