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

namespace spec\Sylius\Component\Resource\Fixtures;

use Sylius\Resource\Model\ResourceInterface;

interface SampleBookResourceInterface extends ResourceInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return int
     */
    public function getRating();

    /**
     * @return string
     */
    public function getTitle();
}
