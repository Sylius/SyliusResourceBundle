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

namespace spec\Sylius\Component\Resource\Grid\State;

use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Component\Grid\View\GridView;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\State\RequestGridProvider;
use Sylius\Resource\Grid\View\Factory\GridViewFactoryInterface;
use Symfony\Component\HttpFoundation\InputBag;
use Symfony\Component\HttpFoundation\Request;

final class RequestGridProviderSpec extends ObjectBehavior
{
    function let(
        GridViewFactoryInterface $gridViewFactory,
        GridProviderInterface $gridProvider,
    ): void {
        $this->beConstructedWith($gridViewFactory, $gridProvider);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(RequestGridProvider::class);
    }

    function it_provides_a_grid_view(
        Request $request,
        GridViewFactoryInterface $gridViewFactory,
        GridProviderInterface $gridProvider,
        Grid $gridDefinition,
        GridView $gridView,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag();

        $gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);

        $gridViewFactory->create($gridDefinition, $context, new Parameters(), [])->willReturn($gridView);

        $this->provide($operation, $context)
            ->shouldReturn($gridView)
        ;
    }

    function it_sets_current_page_from_request(
        Request $request,
        GridViewFactoryInterface $gridViewFactory,
        GridProviderInterface $gridProvider,
        Grid $gridDefinition,
        GridView $gridView,
        Pagerfanta $pagerfanta,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['page' => 42]);

        $gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);

        $gridViewFactory->create($gridDefinition, $context, new Parameters(['page' => 42]), [])->willReturn($gridView);

        $gridView->getData()->willReturn($pagerfanta);
        $pagerfanta->setCurrentPage(42)->willReturn($pagerfanta)->shouldBeCalled();

        $this->provide($operation, $context)
            ->shouldReturn($gridView)
        ;
    }

    function it_throws_an_exception_when_operation_has_no_grid(
        Request $request,
    ): void {
        $operation = new Index(name: 'app_book');

        $this->shouldThrow(new \RuntimeException('Operation has no grid, so you cannot use this provider for operation "app_book"'))
            ->during('provide', [
                $operation,
                new Context(new RequestOption($request->getWrappedObject())),
            ])
        ;
    }

    function it_throws_an_exception_when_operation_does_not_implement_the_grid_aware_interface(
        Request $request,
    ): void {
        $operation = new Create(name: 'app_book');

        $this->shouldThrow(new \LogicException('You can not use a grid if your operation does not implement "Sylius\Component\Resource\Metadata\GridAwareOperationInterface".'))
            ->during('provide', [
                $operation,
                new Context(new RequestOption($request->getWrappedObject())),
            ])
        ;
    }
}
