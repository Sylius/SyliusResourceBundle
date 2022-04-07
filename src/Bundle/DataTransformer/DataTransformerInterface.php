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

namespace Sylius\Bundle\ResourceBundle\DataTransformer;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

interface DataTransformerInterface
{
    /** @psalm-param class-string $to */
    public function transform(object $data, string $to, RequestConfiguration $configuration): object;

    /** @psalm-param class-string $to */
    public function supportsTransformation(object $data, string $to, RequestConfiguration $configuration): bool;
}
