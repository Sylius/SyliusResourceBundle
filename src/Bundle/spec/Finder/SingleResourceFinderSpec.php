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

namespace spec\Sylius\Bundle\ResourceBundle\Finder;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Bundle\ResourceBundle\Finder\SingleResourceFinderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SingleResourceFinderSpec extends ObjectBehavior
{
    function let(SingleResourceProviderInterface $singleResourceProvider): void
    {
        $this->beConstructedWith($singleResourceProvider);
    }

    function it_implements_single_resource_finder_interface(): void
    {
        $this->shouldImplement(SingleResourceFinderInterface::class);
    }

    function it_returns_found_resource(
        SingleResourceProviderInterface $singleResourceProvider,
        RepositoryInterface $repository,
        RequestConfiguration $requestConfiguration,
        ResourceInterface $book
    ): void {
        $singleResourceProvider->get($requestConfiguration, $repository)->willReturn($book);

        $this->findOr404($requestConfiguration, $repository, 'book')->shouldReturn($book);
    }

    function it_throws_an_exception_if_resource_is_not_found(
        SingleResourceProviderInterface $singleResourceProvider,
        RepositoryInterface $repository,
        RequestConfiguration $requestConfiguration
    ): void {
        $singleResourceProvider->get($requestConfiguration, $repository)->willReturn(null);

        $this
            ->shouldThrow(new NotFoundHttpException('The "book" has not been found'))
            ->during('findOr404', [$requestConfiguration, $repository, 'book'])
        ;
    }
}
