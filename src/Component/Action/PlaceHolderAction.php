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

namespace Sylius\Component\Resource\Action;

final class PlaceHolderAction
{
    /**
     * @param mixed $data
     */
    public function __invoke($data = null): mixed
    {
        return $data;
    }
}
