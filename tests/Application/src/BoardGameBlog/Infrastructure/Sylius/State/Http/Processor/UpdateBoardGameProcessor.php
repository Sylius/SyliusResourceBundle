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

namespace App\BoardGameBlog\Infrastructure\Sylius\State\Http\Processor;

use App\BoardGameBlog\Application\Command\UpdateBoardGameCommand;
use App\BoardGameBlog\Domain\ValueObject\BoardGameId;
use App\BoardGameBlog\Domain\ValueObject\BoardGameName;
use App\BoardGameBlog\Infrastructure\Sylius\Resource\BoardGameResource;
use App\Shared\Application\Command\CommandBusInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProcessorInterface;
use Webmozart\Assert\Assert;

final class UpdateBoardGameProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @param BoardGameResource|mixed $data
     */
    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        Assert::isInstanceOf($data, BoardGameResource::class);

        $command = new UpdateBoardGameCommand(
            new BoardGameId($data->id),
            null !== $data->name ? new BoardGameName($data->name) : null,
        );

        $model = $this->commandBus->dispatch($command);

        return BoardGameResource::fromModel($model);
    }
}
