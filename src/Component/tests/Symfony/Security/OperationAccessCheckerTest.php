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
    public static function getGranted(): iterable
    {
        yield [true];
        yield [false];
    }

    private function createTokenMock(array $roleNames = []): TokenInterface
    {
        $token = $this->createMock(TokenInterface::class);
        $token->method('getRoleNames')->willReturn($roleNames);

        return $token;
    }

    private function createOperationMock(?string $securityExpression = 'is_granted("ROLE_ADMIN")'): Operation
    {
        $operation = $this->createMock(Operation::class);
        $operation->method('getSecurity')->willReturn($securityExpression);

        return $operation;
    }

    private function createExpressionLanguageMock(string $expression, bool $result): ExpressionLanguage
    {
        $expressionLanguage = $this->createMock(ExpressionLanguage::class);
        $expressionLanguage
            ->method('evaluate')
            ->with($expression, $this->isType('array'))
            ->willReturn($result);

        return $expressionLanguage;
    }

    #[DataProvider('getGranted')]
    public function testIsGranted(bool $granted): void
    {
        $expressionLanguage = $this->createExpressionLanguageMock('is_granted("ROLE_ADMIN")', $granted);
        $authenticationTrustResolver = $this->createMock(AuthenticationTrustResolverInterface::class);
        $token = $this->createTokenMock([]);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $operation = $this->createOperationMock();

        $checker = new OperationAccessChecker(
            $expressionLanguage,
            $authenticationTrustResolver,
            null,
            $tokenStorage,
        );

        $this->assertSame($granted, $checker->isGranted($operation, new Context()));
    }

    public function testSecurityComponentNotAvailable(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/security" library must be installed to use the "security" attribute.');

        $checker = new OperationAccessChecker(
            $this->createMock(ExpressionLanguage::class),
        );
        $checker->isGranted(new HttpOperation(), new Context());
    }

    public function testExpressionLanguageNotInstalled(): void
    {
        $this->expectException(\LogicException::class);
        $this->expectExceptionMessage('The "symfony/expression-language" library must be installed to use the "security" attribute.');

        $authenticationTrustResolver = $this->createMock(AuthenticationTrustResolverInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($this->createMock(TokenInterface::class));

        $checker = new OperationAccessChecker(
            null,
            $authenticationTrustResolver,
            null,
            $tokenStorage,
        );
        $checker->isGranted(new HttpOperation(), new Context());
    }

    public function testWithoutAuthenticationToken(): void
    {
        $expressionLanguage = $this->createExpressionLanguageMock('is_granted("ROLE_ADMIN")', true);
        $authenticationTrustResolver = $this->createMock(AuthenticationTrustResolverInterface::class);
        $authorizationChecker = $this->createMock(AuthorizationCheckerInterface::class);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn(null);

        $operation = $this->createOperationMock();

        $checker = new OperationAccessChecker(
            $expressionLanguage,
            $authenticationTrustResolver,
            null,
            $tokenStorage,
            $authorizationChecker,
        );

        self::assertTrue($checker->isGranted($operation, new Context()));
    }

    public function testItGrantsAccessWhenOperationHasNoSecurityExpression(): void
    {
        $expressionLanguage = $this->createMock(ExpressionLanguage::class);
        // Expression language should not be called when security is null
        $expressionLanguage->expects($this->never())->method('evaluate');

        $authenticationTrustResolver = $this->createMock(AuthenticationTrustResolverInterface::class);
        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $operation = $this->createOperationMock(null);

        $checker = new OperationAccessChecker(
            $expressionLanguage,
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
        $authenticationTrustResolver = $this->createMock(AuthenticationTrustResolverInterface::class);
        $token = $this->createTokenMock(['ROLE_USER']);

        $tokenStorage = $this->createMock(TokenStorageInterface::class);
        $tokenStorage->method('getToken')->willReturn($token);

        $roleHierarchy = $this->createMock(RoleHierarchyInterface::class);
        $roleHierarchy
            ->expects($this->once())
            ->method('getReachableRoleNames')
            ->with(['ROLE_USER'])
            ->willReturn(['ROLE_USER', 'ROLE_ADMIN']);

        $operation = $this->createOperationMock();

        $checker = new OperationAccessChecker(
            $expressionLanguage,
            $authenticationTrustResolver,
            $roleHierarchy,
            $tokenStorage,
        );

        $this->assertSame($granted, $checker->isGranted($operation, new Context()));
    }
}
