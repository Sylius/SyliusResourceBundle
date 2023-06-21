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

namespace App\BoardGameBlog\Infrastructure\Sylius\State\Http\Provider;

use App\BoardGameBlog\Application\Query\FindBoardGameQuery;
use App\BoardGameBlog\Domain\ValueObject\BoardGameId;
use App\BoardGameBlog\Infrastructure\Sylius\Resource\BoardGameResource;
use App\Shared\Application\Query\QueryBusInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProviderInterface;
use Symfony\Component\Uid\Uuid;
use Webmozart\Assert\Assert;

final class BoardGameItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        $request = $context->get(RequestOption::class)?->request();
        Assert::notNull($request);

        $id = (string) $request->attributes->get('id');

        $model = $this->queryBus->ask(new FindBoardGameQuery(new BoardGameId(Uuid::fromString($id))));

        return null !== $model ? BoardGameResource::fromModel($model) : null;
    }
}
