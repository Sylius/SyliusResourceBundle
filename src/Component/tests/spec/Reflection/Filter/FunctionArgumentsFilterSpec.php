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

namespace spec\Sylius\Resource\Reflection\Filter;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Tests\Dummy\RepositoryWithCallables;
use Sylius\Resource\Reflection\CallableReflection;
use Sylius\Resource\Reflection\Filter\FunctionArgumentsFilter;

final class FunctionArgumentsFilterSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(FunctionArgumentsFilter::class);
    }

    function it_filters_matching_arguments(): void
    {
        $callable = [RepositoryWithCallables::class, 'find'];
        $reflector = CallableReflection::from($callable);

        $this->filter(
            $reflector,
            [
                'id' => 'my_id',
                'foo' => 'fighters',
            ],
        )->shouldReturn([
            'id' => 'my_id',
        ]);
    }
}
