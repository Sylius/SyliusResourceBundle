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

namespace spec\Sylius\Component\Resource\Factory;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Factory\ArgumentParser;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class ArgumentParserSpec extends ObjectBehavior
{
    function let(
        RequestStack $requestStack,
        TokenStorageInterface $tokenStorage,
    ): void {
        $this->beConstructedWith(
            new ExpressionLanguage(),
            $requestStack,
            $tokenStorage,
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ArgumentParser::class);
    }

    function it_parses_request_variable(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
        RequestStack $requestStack,
        Request $request,
    ): void {
        $requestStack->getCurrentRequest()->willReturn($request);

        $request->attributes = new ParameterBag(['id' => '51353e91-5295-4876-a994-cae4b3ff3a7c']);

        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn(null);

        $this->parseExpression("request.attributes.get('id')")->shouldReturn('51353e91-5295-4876-a994-cae4b3ff3a7c');
    }

    function it_parses_token_variable(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
        UserInterface $user,
    ): void {
        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn($user);

        $token->getUserIdentifier()->willReturn('51353e91-5295-4876-a994-cae4b3ff3a7c');

        $this->parseExpression('token.getUserIdentifier()')->shouldReturn('51353e91-5295-4876-a994-cae4b3ff3a7c');
    }

    function it_parses_user_variable(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
        UserInterface $user,
    ): void {
        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn($user);

        $user->getUserIdentifier()->willReturn('51353e91-5295-4876-a994-cae4b3ff3a7c');

        $this->parseExpression('user.getUserIdentifier()')->shouldReturn('51353e91-5295-4876-a994-cae4b3ff3a7c');
    }

    function its_user_variable_can_be_null(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
    ): void {
        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn(null);

        $this->parseExpression('user === null')->shouldReturn(true);
    }
}
