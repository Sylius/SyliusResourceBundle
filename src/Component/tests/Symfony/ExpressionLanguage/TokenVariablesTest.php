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

namespace Sylius\Resource\Tests\Symfony\ExpressionLanguage;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Symfony\ExpressionLanguage\TokenVariables;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class TokenVariablesTest extends TestCase
{
    private TokenStorageInterface $tokenStorage;

    private TokenVariables $tokenVariables;

    protected function setUp(): void
    {
        $this->tokenStorage = $this->createMock(TokenStorageInterface::class);
        $this->tokenVariables = new TokenVariables($this->tokenStorage);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(TokenVariables::class, $this->tokenVariables);
    }

    public function testItReturnsTokenAndUserVars(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $user = $this->createMock(UserInterface::class);

        $this->tokenStorage->method('getToken')->willReturn($token);
        $token->method('getUser')->willReturn($user);

        $result = $this->tokenVariables->getVariables();

        $this->assertSame(['token' => $token, 'user' => $user], $result);
    }

    public function testItReturnsANullTokenIfThereIsNoTokenOnStorage(): void
    {
        $this->tokenStorage->method('getToken')->willReturn(null);

        $result = $this->tokenVariables->getVariables();

        $this->assertInstanceOf(NullToken::class, $result['token']);
    }

    public function testItCanReturnNullAsUser(): void
    {
        $token = $this->createMock(TokenInterface::class);
        $token->method('getUser')->willReturn(null);

        $this->tokenStorage->method('getToken')->willReturn($token);

        $result = $this->tokenVariables->getVariables();

        $this->assertSame(['token' => $token, 'user' => null], $result);
    }

    public function testItThrowsAnExceptionWhenThereIsNoTokenStorage(): void
    {
        $tokenVariables = new TokenVariables(null);

        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/security-bundle" must be installed and configured to use the "token" & "user" attribute. Try running "composer require symfony/security-bundle"');

        $tokenVariables->getVariables();
    }
}
