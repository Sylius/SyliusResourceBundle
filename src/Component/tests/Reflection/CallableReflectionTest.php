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
use ReflectionFunctionAbstract;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Reflection\CallableReflection;

final class CallableReflectionTest extends TestCase
{
    public function testItIsInitializable(): void
    {
        $callableReflection = new CallableReflection();
        $this->assertInstanceOf(CallableReflection::class, $callableReflection);
    }

    public function testItReflectsAnArrayCallable(): void
    {
        $reflection = CallableReflection::from([RepositoryWithCallables::class, 'find']);
        $this->assertInstanceOf(ReflectionFunctionAbstract::class, $reflection);
    }

    public function testItReflectsAClosureCallable(): void
    {
        $reflection = CallableReflection::from(fn (): array => []);
        $this->assertInstanceOf(ReflectionFunctionAbstract::class, $reflection);
    }

    public function testItReflectsAStringCallable(): void
    {
        $reflection = CallableReflection::from('Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables::find');
        $this->assertInstanceOf(ReflectionFunctionAbstract::class, $reflection);
    }

    public function testItReflectsAnInvokableCallable(): void
    {
        $reflection = CallableReflection::from(new RepositoryWithCallables());
        $this->assertInstanceOf(ReflectionFunctionAbstract::class, $reflection);
    }
}
