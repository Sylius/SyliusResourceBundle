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

namespace Sylius\Resource\Tests\Reflection\Filter;

use PHPUnit\Framework\TestCase;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Reflection\CallableReflection;
use Sylius\Resource\Reflection\Filter\FunctionArgumentsFilter;

final class FunctionArgumentsFilterTest extends TestCase
{
    private FunctionArgumentsFilter $functionArgumentsFilter;

    protected function setUp(): void
    {
        $this->functionArgumentsFilter = new FunctionArgumentsFilter();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(FunctionArgumentsFilter::class, $this->functionArgumentsFilter);
    }

    public function testItFiltersMatchingArguments(): void
    {
        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $result = $this->functionArgumentsFilter->filter(
            $reflector,
            [
                'id' => 'my_id',
                'foo' => 'fighters',
            ],
        );

        $this->assertSame(['id' => 'my_id'], $result);
    }
}
