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

namespace App\BoardGameBlog\Application\Command;

use App\BoardGameBlog\Domain\ValueObject\BoardGameName;
use App\Shared\Application\Command\CommandInterface;

final class CreateBoardGameCommand implements CommandInterface
{
    public function __construct(
        public BoardGameName $name,
    ) {
    }
}
