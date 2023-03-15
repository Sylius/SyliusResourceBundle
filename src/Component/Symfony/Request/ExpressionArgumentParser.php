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

namespace Sylius\Component\Resource\Symfony\Request;

use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ExpressionArgumentParser
{
    public function __construct(
        private RequestStack $requestStack,
        private ?ExpressionLanguage $expressionLanguage,
        private ?TokenStorageInterface $tokenStorage,
    ) {
    }

    public function parseExpression(string $expression): mixed
    {
        return $this->expressionLanguage->evaluate($expression, $this->getVariables());
    }

    private function getVariables(): array
    {
        $request = $this->requestStack->getCurrentRequest();

        return [
            'request' => $request,
            'params' => $request?->attributes->get('_route_params'),
            'user' => $this->tokenStorage?->getToken()?->getUser(),
        ];
    }
}
