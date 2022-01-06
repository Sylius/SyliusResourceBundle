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

namespace spec\Sylius\Bundle\ResourceBundle\Checker;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Checker\RequestPermissionCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

final class RequestPermissionCheckerSpec extends ObjectBehavior
{
    function let(AuthorizationCheckerInterface $authorizationChecker): void
    {
        $this->beConstructedWith($authorizationChecker);
    }

    function it_implements_request_permission_checker_interface(): void
    {
        $this->shouldImplement(RequestPermissionCheckerInterface::class);
    }

    function it_does_nothing_if_configuration_has_no_permission(
        AuthorizationCheckerInterface $authorizationChecker,
        RequestConfiguration $configuration
    ): void {
        $configuration->hasPermission()->willReturn(false);

        $authorizationChecker->isGranted(Argument::any())->shouldNotBeCalled();

        $this->isGrantedOr403($configuration, 'permission');
    }

    function it_does_nothing_if_configuration_has_permission_and_it_is_granted(
        AuthorizationCheckerInterface $authorizationChecker,
        RequestConfiguration $configuration
    ): void {
        $configuration->hasPermission()->willReturn(true);
        $configuration->getPermission('permission_name')->willReturn('permission_value');

        $authorizationChecker->isGranted($configuration, 'permission_value')->willReturn(true);

        $this
            ->shouldNotThrow(AccessDeniedException::class)
            ->during('isGrantedOr403', [$configuration, 'permission_name'])
        ;
    }

    function it_throws_exception_if_configuration_has_permission_and_it_is_not_granted(
        AuthorizationCheckerInterface $authorizationChecker,
        RequestConfiguration $configuration
    ): void {
        $configuration->hasPermission()->willReturn(true);
        $configuration->getPermission('permission_name')->willReturn('permission_value');

        $authorizationChecker->isGranted($configuration, 'permission_value')->willReturn(false);

        $this
            ->shouldThrow(AccessDeniedException::class)
            ->during('isGrantedOr403', [$configuration, 'permission_name'])
        ;
    }
}
