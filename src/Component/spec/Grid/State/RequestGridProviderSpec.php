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

namespace spec\Sylius\Component\Resource\Grid\State;

use Pagerfanta\Pagerfanta;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Provider\GridProviderInterface;
use Sylius\Component\Grid\View\GridView;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Grid\State\RequestGridProvider;
use Sylius\Component\Resource\Grid\View\Factory\GridViewFactoryInterface;
use Sylius\Component\Resource\Metadata\Index;
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
        $operation = new Index(grid: 'app_book');

        $gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);

        $gridViewFactory->create($gridDefinition, new Parameters(), [])->willReturn($gridView);

        $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())))
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
        $operation = new Index(grid: 'app_book');

        $request->query = new InputBag(['page' => 42]);

        $gridProvider->get('app_book')->willReturn($gridDefinition);
        $gridDefinition->getDriverConfiguration()->willReturn([]);

        $gridViewFactory->create($gridDefinition, new Parameters(), [])->willReturn($gridView);

        $gridView->getData()->willReturn($pagerfanta);
        $pagerfanta->setCurrentPage(42)->willReturn($pagerfanta)->shouldBeCalled();

        $this->provide($operation, new Context(new RequestOption($request->getWrappedObject())))
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
}
