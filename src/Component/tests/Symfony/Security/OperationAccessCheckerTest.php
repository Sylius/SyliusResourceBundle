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

namespace Sylius\Resource\Tests\Symfony\Security;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Symfony\Security\OperationAccessChecker;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Authorization\ExpressionLanguage;

class OperationAccessCheckerTest extends TestCase
{
    use ProphecyTrait;

    public static function getGranted(): iterable
    {
        yield [true];
        yield [false];
    }

    #[DataProvider('getGranted')]
    public function testIsGranted(bool $granted): void
    {
        $expressionLanguageProphecy = $this->prophesize(ExpressionLanguage::class);
        $expressionLanguageProphecy->evaluate('is_granted("ROLE_ADMIN")', Argument::type('array'))->willReturn($granted)->shouldBeCalled();

        $authenticationTrustResolverProphecy = $this->prophesize(AuthenticationTrustResolverInterface::class);
        $tokenStorageProphecy = $this->prophesize(TokenStorageInterface::class);

        $tokenProphecy = $this->prophesize(TokenInterface::class);
        $token = $tokenProphecy->reveal();
        $tokenProphecy->getUser()->shouldBeCalled();

        $tokenProphecy->getRoleNames()->willReturn([])->shouldBeCalled();

        $tokenStorageProphecy->getToken()->willReturn($token);

        $operation = $this->prophesize(Operation::class);
        $operation->getSecurity()->willReturn('is_granted("ROLE_ADMIN")')->shouldBeCalled();

        $checker = new OperationAccessChecker(
            $expressionLanguageProphecy->reveal(),
            $authenticationTrustResolverProphecy->reveal(),
            null,
            $tokenStorageProphecy->reveal(),
        );
        $this->assertSame($granted, $checker->isGranted($operation->reveal(), new Context()));
    }

    public function testSecurityComponentNotAvailable(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/security" library must be installed to use the "security" attribute.');

        $checker = new OperationAccessChecker(
            $this->prophesize(ExpressionLanguage::class)->reveal(),
        );
        $checker->isGranted(new HttpOperation(), new Context());
    }

    public function testExpressionLanguageNotInstalled(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/expression-language" library must be installed to use the "security" attribute.');

        $authenticationTrustResolverProphecy = $this->prophesize(AuthenticationTrustResolverInterface::class);
        $tokenStorageProphecy = $this->prophesize(TokenStorageInterface::class);
        $tokenStorageProphecy->getToken()->willReturn($this->prophesize(TokenInterface::class)->reveal());

        $checker = new OperationAccessChecker(
            null,
            $authenticationTrustResolverProphecy->reveal(),
            null,
            $tokenStorageProphecy->reveal(),
        );
        $checker->isGranted(new HttpOperation(), new Context());
    }

    public function testWithoutAuthenticationToken(): void
    {
        $expressionLanguageProphecy = $this->prophesize(ExpressionLanguage::class);
        $expressionLanguageProphecy->evaluate('is_granted("ROLE_ADMIN")', Argument::type('array'))->willReturn(true)->shouldBeCalled();

        $authenticationTrustResolverProphecy = $this->prophesize(AuthenticationTrustResolverInterface::class);
        $authorizationCheckerProphecy = $this->prophesize(AuthorizationCheckerInterface::class);
        $tokenStorageProphecy = $this->prophesize(TokenStorageInterface::class);

        $tokenStorageProphecy->getToken()->willReturn(null);

        $operation = $this->prophesize(Operation::class);
        $operation->getSecurity()->willReturn('is_granted("ROLE_ADMIN")')->shouldBeCalled();

        $checker = new OperationAccessChecker(
            $expressionLanguageProphecy->reveal(),
            $authenticationTrustResolverProphecy->reveal(),
            null,
            $tokenStorageProphecy->reveal(),
            $authorizationCheckerProphecy->reveal(),
        );
        self::assertTrue($checker->isGranted($operation->reveal(), new Context()));
    }
}
