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

namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;

class BlogPost implements ResourceInterface
{
    private ?int $id = null;

    private array $currentPlace = ['draft' => 1];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCurrentPlace(): array
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace(array $currentPlace)
    {
        $this->currentPlace = $currentPlace;
    }
}
