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

namespace Sylius\Component\Resource\Symfony\ExpressionLanguage;

use Symfony\Component\Security\Core\Authentication\Token\NullToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class TokenVariables implements VariablesInterface
{
    public function __construct(private ?TokenStorageInterface $tokenStorage = null)
    {
    }

    public function getVariables(): array
    {
        if (null === $this->tokenStorage) {
            throw new \LogicException('The "symfony/security-bundle" must be installed and configured to use the "token" & "user" attribute. Try running "composer require symfony/security-bundle"');
        }

        if (null === $token = $this->tokenStorage->getToken()) {
            $token = new NullToken();
        }

        return [
            'token' => $token,
            'user' => $token->getUser(),
        ];
    }
}
