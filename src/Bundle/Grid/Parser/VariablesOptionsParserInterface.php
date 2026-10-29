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
