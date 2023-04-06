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
use Sylius\Component\Resource\Symfony\ExpressionLanguage\VariablesCollectionInterface;
use Sylius\Component\Resource\Symfony\ExpressionLanguage\VariablesInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

final class ArgumentParser implements ArgumentParserInterface
{
    public function __construct(
        private ExpressionLanguage $expressionLanguage,
        private VariablesCollectionInterface $variablesCollection,
    ) {
    }

    public function parseExpression(string $expression, Resource $resource, mixed $data): mixed
    {
        return $this->expressionLanguage->evaluate($expression, $this->getVariables($resource, $data));
    }

    private function getVariables(Resource $resource, mixed $data): array
    {
        $variables = array_merge($this->variablesCollection->getVariables(), [
            'resource' => $data,
        ]);

        $name = $resource->getName();

        if (null !== $name) {
            $variables[$name] = $data;
        }

        return $variables;
    }
}
