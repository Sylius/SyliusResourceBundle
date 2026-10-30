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
 * Parses the grid action options ("$name", "expr:...", "resource.field") without any request,
 * the "$name" placeholders being resolved from the given variables (see GridContext::getVariables()).
 *
 * BC layer: this method belongs to OptionsParserInterface, but adding it there would break its implementations.
 * It is therefore a separate interface, implemented by OptionsParser, and the grid renderers check for it
 * (falling back to the previous behaviour when the options parser does not implement it).
 * It is meant to be merged into OptionsParserInterface in the next major version.
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
