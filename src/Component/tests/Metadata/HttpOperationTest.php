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

namespace Sylius\Resource\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Metadata\HttpOperation;

final class HttpOperationTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $httpOperation = new HttpOperation();
        $this->assertInstanceOf(HttpOperation::class, $httpOperation);
    }

    public function testItHasNoNameByDefault(): void
    {
        $httpOperation = new HttpOperation();
        $this->assertNull($httpOperation->getName());
    }

    public function testItCouldHaveAName(): void
    {
        $httpOperation = (new HttpOperation())->withName('create');
        $this->assertSame('create', $httpOperation->getName());
    }

    public function testItHasNoMethodsByDefault(): void
    {
        $httpOperation = new HttpOperation();
        $this->assertNull($httpOperation->getMethods());
    }

    public function testItCouldHaveMethods(): void
    {
        $httpOperation = (new HttpOperation())->withMethods(['POST', 'GET']);
        $this->assertSame(['POST', 'GET'], $httpOperation->getMethods());
    }

    public function testItHasNoPathByDefault(): void
    {
        $httpOperation = new HttpOperation();
        $this->assertNull($httpOperation->getPath());
    }

    public function testItCouldHaveAPath(): void
    {
        $httpOperation = (new HttpOperation())->withPath('you_should_not_pass');
        $this->assertSame('you_should_not_pass', $httpOperation->getPath());
    }

    public function testItHasNoRoutePrefixByDefault(): void
    {
        $httpOperation = new HttpOperation();
        $this->assertNull($httpOperation->getRoutePrefix());
    }

    public function testItCouldHaveARoutePrefix(): void
    {
        $httpOperation = (new HttpOperation())->withRoutePrefix('/admin');
        $this->assertSame('/admin', $httpOperation->getRoutePrefix());
    }

    public function testItHasNoTemplateByDefault(): void
    {
        $httpOperation = new HttpOperation();
        $this->assertNull($httpOperation->getTemplate());
    }

    public function testItCouldHaveATemplate(): void
    {
        $httpOperation = (new HttpOperation())->withTemplate('book/show.html.twig');
        $this->assertSame('book/show.html.twig', $httpOperation->getTemplate());
    }

    public function testItCanBeConstructedWithAName(): void
    {
        $httpOperation = new HttpOperation(name: 'create');
        $this->assertSame('create', $httpOperation->getName());
    }
}
