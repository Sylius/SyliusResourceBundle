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

namespace Sylius\Bundle\ResourceBundle\Controller;

/**
 * BC layer: parses the legacy parameters ("$name", "expr:...", "!!int $name") without any request.
 *
 * The "$name" placeholders are resolved from the given variables (see GridContext::getVariables()).
 * This method belongs to ParametersParserInterface, but adding it there would break its implementations,
 * so it is a separate interface, implemented by ParametersParser.
 *
 * It will go away with ParametersParserInterface and the legacy syntax, replaced by the "@=" expressions
 * (see LegacyGridExpressionParser).
 *
 * @experimental
 */
interface VariablesParametersParserInterface
{
    /**
     * @param array<array-key, mixed> $parameters
     * @param array<string, mixed> $variables
     *
     * @return array<array-key, mixed>
     */
    public function parseVariablesValues(array $parameters, array $variables): array;
}
