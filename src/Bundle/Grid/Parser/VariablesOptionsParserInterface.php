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

namespace Sylius\Bundle\ResourceBundle\Grid\Parser;

/**
 * BC layer: parses the legacy grid action options ("$name", "expr:...", "resource.field") without any request.
 *
 * The "$name" placeholders are resolved from the given variables (see GridContext::getVariables()).
 * This method belongs to OptionsParserInterface, but adding it there would break its implementations,
 * so it is a separate interface, implemented by OptionsParser. The grid renderers check for it,
 * and fall back to the previous behaviour when the options parser does not implement it.
 *
 * It will go away with OptionsParserInterface and the legacy syntax, replaced by the "@=" expressions
 * (see LegacyGridExpressionParser).
 *
 * @experimental
 */
interface VariablesOptionsParserInterface
{
    /**
     * @param array<array-key, mixed> $parameters
     * @param array<string, mixed> $variables
     *
     * @return array<array-key, mixed>
     */
    public function parseOptionsWithVariables(array $parameters, array $variables, mixed $data = null): array;
}
