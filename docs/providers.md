# Providers

Providers retrieve data from your persistence layer.

<!-- TOC -->
* [Providers](#providers)
  * [Default providers](#default-providers)
    * [Custom repository method](#custom-repository-method)
    * [Custom repository arguments](#custom-repository-arguments)
  * [Custom providers](#custom-providers)
  * [Disable providing data](#disable-providing-data)
<!-- TOC -->

## Default providers

When your resource is a Doctrine entity, there's a default provider `Sylius\Component\Resource\Symfony\Request\State\Provider` which is already configured to your operations.

As it uses the Doctrine repository configured on your resource, some default repository methods are used.

| Operation   | Repository method |
|-------------|-------------------|
| index       | createPaginator   |
| show        | findOneBy         |
| update      | findOneBy         |
| delete      | findOneBy         |
| bulk delete | findById          |

### Custom repository method

You can customize the method to use.

```php
// src/Entity/Customer.php

declare(strict_types=1);

namespace App\Entity;

use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Model\ResourceInterface;

#[AsResource]
#[Show(repositoryMethod: 'findOneByEmail')]
final class Customer implements ResourceInterface
{
    // [...]
}
```

### Custom repository arguments

You can pass arguments to your repository method.

3 variables are available:

* `request`: to retrieve data from the request via `Symfony\Component\HttpFoundation\Request`
* `token`: to retrieve data from the authentication token via `Symfony\Component\Security\Core\Authentication\Token\TokenInterface`
* `user`: to retrieve data from the logged-in user via `Symfony\Component\Security\Core\User\UserInterface`

It uses the [Symfony expression language](https://symfony.com/doc/current/components/expression_language.html) component.

```php
// src/Entity/Customer.php

declare(strict_types=1);

namespace App\Entity;

use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Model\ResourceInterface;

#[AsResource]
#[Show(repositoryMethod: 'findOneByEmail', repositoryArguments: ['email' => "request.attributes.get('email')"])]
final class Customer implements ResourceInterface
{
    // [...]
}
```

## Custom providers

Custom providers are useful to customize your logic to retrieve data and for an advanced usage such as an hexagonal architecture.

As an example, let's configure a `BoardGameItemProvider` on a `BoardGameResource` which is not a Doctrine entity.

```php
// src/BoardGameBlog/Infrastructure/Sylius/State/Provider/BoardGameItemProvider.php

declare(strict_types=1);

namespace App\BoardGameBlog\Infrastructure\Sylius\State\Provider;

use Sylius\Resource\State\ProviderInterface;

final class BoardGameItemProvider implements ProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $request = $context->get(RequestOption::class)?->request();
        Assert::notNull($request);

        $id = (string) $request->attributes->get('id');

        $model = $this->queryBus->ask(new FindBoardGameQuery(new BoardGameId(Uuid::fromString($id))));

        return null !== $model ? BoardGameResource::fromModel($model) : null;
    }
}
```

Use this provider on your operation.

```php
// src/BoardGameBlog/Infrastructure/Sylius/Resource/BoardGameResource.php

declare(strict_types=1);

namespace App\BoardGameBlog\Infrastructure\Sylius\Resource;

use App\BoardGameBlog\Infrastructure\Sylius\State\Provider\BoardGameItemProvider;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Show;
use Sylius\Resource\Model\ResourceInterface;

#[AsResource]
#[Show(provider: BoardGameItemProvider::class)]
final class BoardGameResource implements ResourceInterface
{
    // [...]
}
```

## Disable providing data

In some cases, you may want not to read data.

For example, in a delete operation, you can implement your custom delete processor without reading it before.

```php
// src/BoardGameBlog/Infrastructure/Sylius/Resource/BoardGameResource.php

declare(strict_types=1);

namespace App\Entity;

use App\BoardgameBlog\Infrastructure\Sylius\State\Provider\DeleteBoardGameProcessor;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Delete;
use Sylius\Resource\Model\ResourceInterface;

#[AsResource]
#[Delete(
    processor: DeleteBoardGameProcessor::class,
    read: false,
 )]
final class BoardGameResource implements ResourceInterface
{
    // [...]
}
```

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

**[Go back to the documentation's index](index.md)**

**[> Next chapter: Processors](processors.md)**
