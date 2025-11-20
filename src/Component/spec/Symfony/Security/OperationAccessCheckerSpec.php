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
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class OperationAccessCheckerTest extends TestCase
{
    use ProphecyTrait;

    public static function getGranted(): iterable
    {
        yield [true];
        yield [false];
    }

    private function createTokenMock(array $roleNames = []): TokenInterface
    {
        $tokenProphecy = $this->prophesize(TokenInterface::class);
        $tokenProphecy->getUser()->shouldBeCalled();
        $tokenProphecy->getRoleNames()->willReturn($roleNames)->shouldBeCalled();

        return $tokenProphecy->reveal();
    }

    private function createOperationMock(?string $securityExpression = 'is_granted("ROLE_ADMIN")'): Operation
    {
        $operation = $this->prophesize(Operation::class);
        $operation->getSecurity()->willReturn($securityExpression)->shouldBeCalled();

        return $operation->reveal();
    }

    private function createExpressionLanguageMock(string $expression, bool $result): ExpressionLanguage
    {
        $expressionLanguageProphecy = $this->prophesize(ExpressionLanguage::class);
        $expressionLanguageProphecy
            ->evaluate($expression, Argument::type('array'))
            ->willReturn($result)
            ->shouldBeCalled();

        return $expressionLanguageProphecy->reveal();
    }

    #[DataProvider('getGranted')]
    public function testIsGranted(bool $granted): void
    {
        $expressionLanguage = $this->createExpressionLanguageMock('is_granted("ROLE_ADMIN")', $granted);
        $authenticationTrustResolver = $this->prophesize(AuthenticationTrustResolverInterface::class)->reveal();
        $token = $this->createTokenMock([]);

        $tokenStorageProphecy = $this->prophesize(TokenStorageInterface::class);
        $tokenStorageProphecy->getToken()->willReturn($token);

        $operation = $this->createOperationMock();

        $checker = new OperationAccessChecker(
            $expressionLanguage,
            $authenticationTrustResolver,
            null,
            $tokenStorageProphecy->reveal(),
        );

        $this->assertSame($granted, $checker->isGranted($operation, new Context()));
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
        $expressionLanguage = $this->createExpressionLanguageMock('is_granted("ROLE_ADMIN")', true);
        $authenticationTrustResolver = $this->prophesize(AuthenticationTrustResolverInterface::class)->reveal();
        $authorizationChecker = $this->prophesize(AuthorizationCheckerInterface::class)->reveal();

        $tokenStorageProphecy = $this->prophesize(TokenStorageInterface::class);
        $tokenStorageProphecy->getToken()->willReturn(null);

        $operation = $this->createOperationMock();

        $checker = new OperationAccessChecker(
            $expressionLanguage,
            $authenticationTrustResolver,
            null,
            $tokenStorageProphecy->reveal(),
            $authorizationChecker,
        );

        self::assertTrue($checker->isGranted($operation, new Context()));
    }

    public function testItGrantsAccessWhenOperationHasNoSecurityExpression(): void
    {
        $expressionLanguageProphecy = $this->prophesize(ExpressionLanguage::class);
        // Expression language should not be called when security is null
        $expressionLanguageProphecy->evaluate(Argument::any(), Argument::any())->shouldNotBeCalled();

        $authenticationTrustResolver = $this->prophesize(AuthenticationTrustResolverInterface::class)->reveal();
        $tokenStorage = $this->prophesize(TokenStorageInterface::class)->reveal();
        $operation = $this->createOperationMock(null);

        $checker = new OperationAccessChecker(
            $expressionLanguageProphecy->reveal(),
            $authenticationTrustResolver,
            null,
            $tokenStorage,
        );

        // When security expression is null, should return true (access granted)
        self::assertTrue($checker->isGranted($operation, new Context()));
    }

    #[DataProvider('getGranted')]
    public function testIsGrantedWithRoleHierarchy(bool $granted): void
    {
        $expressionLanguage = $this->createExpressionLanguageMock('is_granted("ROLE_ADMIN")', $granted);
        $authenticationTrustResolver = $this->prophesize(AuthenticationTrustResolverInterface::class)->reveal();
        $token = $this->createTokenMock(['ROLE_USER']);

        $tokenStorageProphecy = $this->prophesize(TokenStorageInterface::class);
        $tokenStorageProphecy->getToken()->willReturn($token);

        $roleHierarchyProphecy = $this->prophesize(RoleHierarchyInterface::class);
        $roleHierarchyProphecy
            ->getReachableRoleNames(['ROLE_USER'])
            ->willReturn(['ROLE_USER', 'ROLE_ADMIN'])
            ->shouldBeCalled();

        $operation = $this->createOperationMock();

        $checker = new OperationAccessChecker(
            $expressionLanguage,
            $authenticationTrustResolver,
            $roleHierarchyProphecy->reveal(),
            $tokenStorageProphecy->reveal(),
        );

        $this->assertSame($granted, $checker->isGranted($operation, new Context()));
    }
}
