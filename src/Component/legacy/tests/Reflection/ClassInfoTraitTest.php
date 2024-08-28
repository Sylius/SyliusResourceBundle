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

namespace Reflection;

use App\Entity\Book;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Reflection\ClassInfoTrait;

final class ClassInfoTraitTest extends TestCase
{
    private function getClassInfoTraitImplementation(): object
    {
        return new class() {
            use ClassInfoTrait {
                ClassInfoTrait::getRealClassName as public;
            }
        };
    }

    /**
     * @dataProvider getValidClasses
     */
    public function testRealClassName(string $class): void
    {
        $classInfo = $this->getClassInfoTraitImplementation();

        $this->assertSame(Book::class, $classInfo->getRealClassName($class));
    }

    /**
     * @dataProvider getInvalidClasses
     */
    public function testThrowsExceptionIfUnproxiedClassDoNotExist(string $class): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $classInfo = $this->getClassInfoTraitImplementation();

        $classInfo->getRealClassName($class);
    }

    public function getInvalidClasses(): Iterable
    {
        yield ['class' => 'Proxies\__CG__\App\Entity\Book1'];
        yield ['class' => 'MongoDBODMProxies\__PM__\App\Entity\Book1\Generated'];
    }

    public function getValidClasses(): Iterable
    {
        yield ['class' => 'Proxies\__CG__\App\Entity\Book'];
        yield ['class' => 'MongoDBODMProxies\__PM__\App\Entity\Book\Generated'];
    }

    public function testUnmarkedRealClassName(): void
    {
        $classInfo = $this->getClassInfoTraitImplementation();

        $this->assertSame(Book::class, $classInfo->getRealClassName(Book::class));
    }
}
