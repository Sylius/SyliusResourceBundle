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

namespace spec\Sylius\Bundle\ResourceBundle\Creator;

use FOS\RestBundle\View\View;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\ViewHandlerInterface;
use Sylius\Bundle\ResourceBundle\Creator\RestViewCreatorInterface;
use Symfony\Component\HttpFoundation\Response;

final class RestViewCreatorSpec extends ObjectBehavior
{
    function let(ViewHandlerInterface $viewHandler): void
    {
        $this->beConstructedWith($viewHandler);
    }

    function it_implements_rest_view_creator_interface(): void
    {
        $this->shouldImplement(RestViewCreatorInterface::class);
    }

    function it_creates_rest_view_for_given_data_and_configuration(
        ViewHandlerInterface $viewHandler,
        RequestConfiguration $requestConfiguration,
        Response $response
    ): void {
        $viewHandler->handle($requestConfiguration, Argument::that(function (View $view): bool {
            return $view->getData() === 'data';
        }))->willReturn($response);

        $this->createRestView($requestConfiguration, 'data')->shouldReturn($response);
    }

    function it_creates_rest_view_with_given_status_code(
        ViewHandlerInterface $viewHandler,
        RequestConfiguration $requestConfiguration,
        Response $response
    ): void {
        $viewHandler->handle($requestConfiguration, Argument::that(function (View $view): bool {
            return
                $view->getData() === 'data' &&
                $view->getStatusCode() === Response::HTTP_OK
            ;
        }))->willReturn($response);

        $this->createRestView($requestConfiguration, 'data', Response::HTTP_OK)->shouldReturn($response);
    }

    function it_throws_an_exception_if_view_handler_does_not_exist(RequestConfiguration $requestConfiguration): void
    {
        $this->beConstructedWith(null);

        $this
            ->shouldThrow(new \LogicException(
                'You can not use the "non-html" request if FriendsOfSymfony Rest Bundle is not available. Try running "composer require friendsofsymfony/rest-bundle".'
            ))
            ->during('createRestView', [$requestConfiguration, 'data'])
        ;
    }
}
