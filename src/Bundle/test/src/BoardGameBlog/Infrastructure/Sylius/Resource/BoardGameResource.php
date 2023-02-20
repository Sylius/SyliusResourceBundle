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

namespace App\BoardGameBlog\Infrastructure\Sylius\Resource;

use App\BoardGameBlog\Domain\Model\BoardGame;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Constraints as Assert;

#[Resource(
    alias: 'app.board_game',
    section: 'admin',
    templatesDir: 'crud',
    routePrefix: '/admin',
)]
final class BoardGameResource implements ResourceInterface
{
    public function __construct(
        public ?AbstractUid $id = null,

        #[Assert\NotNull]
        #[Assert\Length(min: 1, max: 255)]
        public ?string $name = null,
    ) {
    }

    public static function fromModel(BoardGame $boardGame): static
    {
        return new self(
            $boardGame->id()->value,
            $boardGame->name()->value,
        );
    }

    public function getId(): ?string
    {
        return $this->id?->__toString();
    }
}
