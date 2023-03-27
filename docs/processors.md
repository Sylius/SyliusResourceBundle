# Processors

Processors process data: send an email, persist to storage, add to queue etc.

<!-- TOC -->
* [Default processors](#default-processors)
* [Custom processors](#custom-processors)
<!-- TOC -->

## Default processors

When your resource is a Doctrine entity, there are default processors which are already configured to your operations.

As it uses the Doctrine repository configured on your resource, it will automatically flush data for you.

| Operation   | Processor                                                        |
|-------------|------------------------------------------------------------------|
| create      | Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor |
| update      | Sylius\Component\Resource\Doctrine\Common\State\PersistProcessor |
| delete      | Sylius\Component\Resource\Doctrine\Common\State\RemoveProcessor  |
| bulk delete | Sylius\Component\Resource\Doctrine\Common\State\RemoveProcessor  |

## Custom processors

Custom processors are useful to customize your logic to persist data to storage and for an advanced usage such as an hexagonal architecture.

As an example, let's configure a `DeleteBoardGameProcessor` on a `BoardGameResource` which is not a Doctrine entity.

```php
// src/BoardGameBlog/Infrastructure/Sylius/State/Processor/DeleteBoardGameProcessor.php

namespace App\BoardGameBlog\Infrastructure\Sylius\State\Processor;

final class DeleteBoardGameProcessor implements ProcessorInterface
{
    public function __construct(
        private CommandBusInterface $commandBus,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        Assert::isInstanceOf($data, BoardGameResource::class);

        $this->commandBus->dispatch(new DeleteBoardGameCommand(new BoardGameId($data->id)));

        return null;
    }
}
```

Use this processor on your operation.

```php
// src/BoardGameBlog/Infrastructure/Sylius/Resource/BoardGameResource.php

namespace App\BoardGameBlog\Infrastructure\Sylius\Resource;

#[Resource(
    alias: 'app.board_game',
    section: 'admin',
    formType: BoardGameType::class,
    templatesDir: 'crud',
    routePrefix: '/admin',
)]
#[Delete(processor: DeleteBoardGameProcessor::class)]
final class BoardGameResource implements ResourceInterface
```

**[Go back to the documentation's index](index.md)**
