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
use App\BoardGameBlog\Infrastructure\Sylius\State\Http\Processor\CreateBoardGameProcessor;
use App\BoardGameBlog\Infrastructure\Sylius\State\Http\Processor\DeleteBoardGameProcessor;
use App\BoardGameBlog\Infrastructure\Sylius\State\Http\Processor\UpdateBoardGameProcessor;
use App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider\BoardGameCollectionProvider;
use App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider\BoardGameItemProvider;
use App\BoardGameBlog\Infrastructure\Symfony\Form\Type\BoardGameType;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Validator\Constraints as Assert;

#[Resource(
    alias: 'app.board_game',
    section: 'admin',
    formType: BoardGameType::class,
    templatesDir: 'crud',
    routePrefix: '/admin',
)]
#[Create(
    processor: CreateBoardGameProcessor::class,
)]
#[Update(
    provider: BoardGameItemProvider::class,
    processor: UpdateBoardGameProcessor::class,
)]
#[Index(
    provider: BoardGameCollectionProvider::class,
    grid: 'app_board_game',
)]
#[Show(
    template: 'board_game/show.html.twig',
    provider: BoardGameItemProvider::class,
)]
#[Delete(
    provider: BoardGameItemProvider::class,
    processor: DeleteBoardGameProcessor::class,
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
