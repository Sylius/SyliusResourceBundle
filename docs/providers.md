# Providers

Providers retrieve data from your persistence layer.

<!-- TOC -->
* [Default providers](#default-providers)
  * [Custom repository method](#custom-repository-method)
  * [Custom repository arguments](#custom-repository-arguments)
* [Custom providers](#custom-providers)
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
// src/BoardGameBlog/Infrastructure/Sylius/Resource/BoardGameResource.php

declare(strict_types=1);

namespace App\Entity;

#[Resource]
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
// src/BoardGameBlog/Infrastructure/Sylius/Resource/BoardGameResource.php

declare(strict_types=1);

namespace App\Entity;

#[Resource]
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
```

Use this provider on your operation.

```php
// src/BoardGameBlog/Infrastructure/Sylius/Resource/BoardGameResource.php

declare(strict_types=1);

namespace App\BoardGameBlog\Infrastructure\Sylius\Resource;

use App\BoardGameBlog\Infrastructure\Sylius\State\Provider\BoardGameItemProvider;

#[Resource]
#[Show(provider: BoardGameItemProvider::class)]
final class BoardGameResource implements ResourceInterface
{
    // [...]
}
```

**[Go back to the documentation's index](index.md)**
