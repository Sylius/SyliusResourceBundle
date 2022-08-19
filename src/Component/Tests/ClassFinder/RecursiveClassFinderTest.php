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

namespace ClassFinder;

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sylius\Component\Resource\Annotation\SyliusResource;
use Sylius\Component\Resource\ClassFinder\RecursiveClassFinder;
use Sylius\Component\Resource\Tests\Dummy\DummyClassOne;
use Sylius\Component\Resource\Tests\Dummy\DummyClassTwo;
use Sylius\Component\Resource\Tests\Dummy\Nested\DummyClassThree;
use Symfony\Component\Finder\Finder;

final class RecursiveClassFinderTest extends TestCase
{
    /** @test */
    public function it_returns_all_classes_from_directory_recursively(): void
    {
        $classFinder = new RecursiveClassFinder(new Finder());
        $classesInDirectory = $classFinder->getFromDirectories([__DIR__ . '/../Dummy']);
        $classesArray = iterator_to_array($classesInDirectory);

        self::assertCount(3, $classesArray);
        self::assertArrayHasKey(DummyClassOne::class, $classesArray);
        self::assertArrayHasKey(DummyClassTwo::class, $classesArray);
        self::assertArrayHasKey(DummyClassThree::class, $classesArray);
        self::assertInstanceOf(ReflectionClass::class, $classesArray[DummyClassOne::class]);
        self::assertInstanceOf(ReflectionClass::class, $classesArray[DummyClassTwo::class]);
        self::assertInstanceOf(ReflectionClass::class, $classesArray[DummyClassThree::class]);
    }

    /** @test */
    public function it_returns_all_classes_from_directory_recursively_with_attribute(): void
    {
        $classFinder = new RecursiveClassFinder(new Finder());
        $classesInDirectory = $classFinder->getFromDirectoriesWithAttribute([__DIR__ . '/../Dummy'], SyliusResource::class);
        $classesArray = iterator_to_array($classesInDirectory);

        self::assertCount(1, $classesArray);
        self::assertArrayHasKey(DummyClassThree::class, $classesArray);
        self::assertInstanceOf(ReflectionClass::class, $classesArray[DummyClassThree::class]);
    }
}
