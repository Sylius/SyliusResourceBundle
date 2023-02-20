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

namespace App\BoardGameBlog\Domain\Model;

use App\BoardGameBlog\Domain\ValueObject\BoardGameId;
use App\BoardGameBlog\Domain\ValueObject\BoardGameName;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class BoardGame
{
    #[ORM\Embedded(columnPrefix: false)]
    private BoardGameId $id;

    public function __construct(
        #[ORM\Embedded(columnPrefix: false)]
        private BoardGameName $name,
    ) {
        $this->id = new BoardGameId();
    }

    public function update(
        ?BoardGameName $name = null,
    ): void {
        $this->name = $name ?? $this->name;
    }

    public function id(): BoardGameId
    {
        return $this->id;
    }

    public function name(): BoardGameName
    {
        return $this->name;
    }
}
