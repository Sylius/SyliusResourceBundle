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

namespace App\BoardGameBlog\Infrastructure\Sylius\Resource\Mutator;

use App\BoardGameBlog\Infrastructure\Sylius\State\Http\Processor\CreateBoardGameProcessor;
use Sylius\Resource\Metadata\AsOperationMutator;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\OperationMutatorInterface;

#[AsOperationMutator('app_admin_board_game_create')]
final class CreateBoardGameProcessorMutator implements OperationMutatorInterface
{
    public function __invoke(Operation $operation): Operation
    {
        return $operation->withProcessor(CreateBoardGameProcessor::class);
    }
}
