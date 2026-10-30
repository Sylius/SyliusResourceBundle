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
 * Parses parameters ("$name", "expr:...", "!!int $name") without any request,
 * the "$name" placeholders being resolved from the given variables (see GridContext::getVariables()).
 *
 * BC layer: this method belongs to ParametersParserInterface, but adding it there would break its implementations.
 * It is therefore a separate interface, implemented by ParametersParser.
 * Like ParametersParserInterface, it is part of the legacy expression syntax, which is meant to be replaced
 * by the ArgumentParser of the new metadata-based routing: it will go away with ParametersParserInterface.
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
