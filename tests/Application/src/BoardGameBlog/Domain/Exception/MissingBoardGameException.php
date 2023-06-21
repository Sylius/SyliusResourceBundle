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

namespace App\BoardGameBlog\Domain\Exception;

use App\BoardGameBlog\Domain\ValueObject\BoardGameId;

final class MissingBoardGameException extends \RuntimeException
{
    public function __construct(BoardGameId $id, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('Cannot find board game with id %s', (string) $id), $code, $previous);
    }
}
