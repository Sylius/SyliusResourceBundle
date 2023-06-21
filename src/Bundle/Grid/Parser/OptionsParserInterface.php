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

use Symfony\Component\HttpFoundation\Request;

interface OptionsParserInterface
{
    /**
     * @param mixed $data
     */
    public function parseOptions(array $parameters, Request $request, $data = null): array;
}
