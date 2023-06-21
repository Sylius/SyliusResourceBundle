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

use App\BoardGameBlog\Application\Command\CreateBoardGameCommand;
use App\BoardGameBlog\Domain\Model\BoardGame;
use App\BoardGameBlog\Domain\ValueObject\BoardGameName;
use App\BoardGameBlog\Infrastructure\Sylius\Resource\BoardGameResource;
use App\Shared\Application\Command\CommandBusInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProcessorInterface;
use Webmozart\Assert\Assert;

final class CreateBoardGameProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    /**
     * @param BoardGameResource|mixed $data
     */
    public function process(mixed $data, Operation $operation, Context $context): BoardGameResource
    {
        Assert::isInstanceOf($data, BoardGameResource::class);

        Assert::notNull($data->name);

        $command = new CreateBoardGameCommand(
            new BoardGameName($data->name),
        );

        /** @var BoardGame $model */
        $model = $this->commandBus->dispatch($command);

        return BoardGameResource::fromModel($model);
    }
}
