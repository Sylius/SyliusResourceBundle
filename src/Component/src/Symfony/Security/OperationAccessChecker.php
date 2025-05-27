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

namespace Sylius\Resource\Symfony\Security;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\OperationAccessCheckerInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Security\Core\Authentication\AuthenticationTrustResolverInterface;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

final class OperationAccessChecker implements OperationAccessCheckerInterface
{
    public function __construct(
        private readonly ?ExpressionLanguage $expressionLanguage = null,
        private readonly ?AuthenticationTrustResolverInterface $authenticationTrustResolver = null,
        private readonly ?RoleHierarchyInterface $roleHierarchy = null,
        private readonly ?TokenStorageInterface $tokenStorage = null,
        private readonly ?AuthorizationCheckerInterface $authorizationChecker = null,
    ) {
    }

    public function isGranted(Operation $operation, Context $context, array $extraVariables = []): bool
    {
        if (null === $this->tokenStorage || null === $this->authenticationTrustResolver) {
            throw new \LogicException('The "symfony/security" library must be installed to use the "security" attribute.');
        }

        if (null === $this->expressionLanguage) {
            throw new \LogicException('The "symfony/expression-language" library must be installed to use the "security" attribute.');
        }

        $expression = $operation->getSecurity();
        if (null === $expression) {
            return true;
        }

        $token = $this->tokenStorage->getToken();
        if (null === $token) {
            $token = new NullToken();
        }

        $variables = array_merge($extraVariables, $this->getVariables($token));

        return (bool) $this->expressionLanguage->evaluate($expression, $variables);
    }

    /**
     * @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/Security/Core/Authorization/Voter/ExpressionVoter.php
     */
    private function getVariables(TokenInterface $token): array
    {
        $roleNames = $token->getRoleNames();

        if (null !== $this->roleHierarchy) {
            $roleNames = $this->roleHierarchy->getReachableRoleNames($roleNames);
        }

        return [
            'token' => $token,
            'user' => $token->getUser(),
            'roles' => $roleNames,
            'trust_resolver' => $this->authenticationTrustResolver,
            'auth_checker' => $this->authorizationChecker, // needed for the is_granted expression function
        ];
    }
}
