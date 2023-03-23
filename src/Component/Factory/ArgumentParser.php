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

namespace Sylius\Component\Resource\Factory;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

final class ArgumentParser
{
    public function __construct(
        private ExpressionLanguage $expressionLanguage,
        private TokenStorageInterface $tokenStorage,
    ) {
    }

    public function parseExpression(string $expression): mixed
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            $token = new NullToken();
        }

        return $this->expressionLanguage->evaluate($expression, $this->getVariables($token));
    }

    private function getVariables(TokenInterface $token): array
    {
        return [
            'token' => $token,
            'user' => $token->getUser(),
        ];
    }
}
