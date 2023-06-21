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

namespace App\BoardGameBlog\Application\Query;

use App\BoardGameBlog\Domain\ValueObject\BoardGameId;
use App\Shared\Application\Query\QueryInterface;

final class FindBoardGameQuery implements QueryInterface
{
    public function __construct(
        public BoardGameId $id,
    ) {
    }
}
