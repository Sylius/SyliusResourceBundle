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

use App\Entity\Book;
use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Reflection\ClassInfoTrait;

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

    public function testDoctrineRealClassName(): void
    {
        $classInfo = $this->getClassInfoTraitImplementation();

        $this->assertSame(Book::class, $classInfo->getRealClassName('Proxies\__CG__\App\Entity\Book'));
    }

    public function testProxyManagerRealClassName(): void
    {
        $classInfo = $this->getClassInfoTraitImplementation();

        $this->assertSame(Book::class, $classInfo->getRealClassName('MongoDBODMProxies\__PM__\App\Entity\Book\Generated'));
    }

    public function testUnmarkedRealClassName(): void
    {
        $classInfo = $this->getClassInfoTraitImplementation();

        $this->assertSame(Book::class, $classInfo->getRealClassName(Book::class));
    }
}
