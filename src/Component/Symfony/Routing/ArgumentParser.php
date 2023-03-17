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

namespace Sylius\Component\Resource\Symfony\Routing;

use Sylius\Component\Resource\Metadata\Resource;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ArgumentParser
{
    public function __construct(private ExpressionLanguage $expressionLanguage)
    {
    }

    public function parseExpression(string $expression, Resource $resource, mixed $data): mixed
    {
        return $this->expressionLanguage->evaluate($expression, $this->getVariables($resource, $data));
    }

    private function getVariables(Resource $resource, mixed $data): array
    {
        $variables = [
            'resource' => $data,
        ];

        $name = $resource->getName();

        if (null !== $name) {
            $variables[$name] = $data;
        }

        return $variables;
    }
}
