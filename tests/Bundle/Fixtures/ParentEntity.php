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

namespace Sylius\Bundle\ResourceBundle\Tests\Fixtures;

use Sylius\Resource\Model\ResourceInterface;

class ParentEntity implements ResourceInterface
{
    private ?int $id = null;

    private ?object $relatedEntity = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}
