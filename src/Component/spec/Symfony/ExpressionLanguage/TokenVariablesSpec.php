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

namespace spec\Sylius\Component\Resource\Symfony\ExpressionLanguage;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\TokenVariables;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class TokenVariablesSpec extends ObjectBehavior
{
    function let(TokenStorageInterface $tokenStorage): void
    {
        $this->beConstructedWith($tokenStorage);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(TokenVariables::class);
    }

    function it_returns_token_and_user_vars(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
        UserInterface $user,
    ): void {
        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn($user);

        $this->getVariables()->shouldReturn([
            'token' => $token,
            'user' => $user,
        ]);
    }

    function it_returns_a_null_token_if_there_is_no_token_on_storage(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
    ): void {
        $tokenStorage->getToken()->willReturn(null);

        $token->getUser()->willReturn(null);

        $this->getVariables()['token']->shouldHaveType(NullToken::class);
    }

    function it_can_return_null_as_user(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
        UserInterface $user,
    ): void {
        $tokenStorage->getToken()->willReturn($token);

        $token->getUser()->willReturn(null);

        $this->getVariables()->shouldReturn([
            'token' => $token,
            'user' => null,
        ]);
    }

    function it_throws_an_exception_when_there_is_no_token_storage(
        TokenStorageInterface $tokenStorage,
        TokenInterface $token,
        UserInterface $user,
    ): void {
        $this->beConstructedWith(null);

        $this->shouldThrow(new \LogicException('The "symfony/security-bundle" must be installed and configured to use the "token" & "user" attribute. Try running "composer require symfony/security-bundle"'))
            ->during('getVariables')
        ;
    }
}
