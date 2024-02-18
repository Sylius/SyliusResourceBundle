# Resource factories

Resource factories are used on Create operations to instantiate your resource.

<!-- TOC -->
* [Resource factories](#resource-factories)
  * [Default factory for your resource](#default-factory-for-your-resource)
  * [Inject the factory in your service](#inject-the-factory-in-your-service)
  * [Define your custom factory](#define-your-custom-factory)
  * [Use your custom method](#use-your-custom-method)
  * [Pass arguments to your method](#pass-arguments-to-your-method)
  * [Use a factory without declaring it](#use-a-factory-without-declaring-it-)
  * [Use a callable for your custom factory](#use-a-callable-for-your-custom-factory)
<!-- TOC -->


## Default factory for your resource

By default, a resource factory is defined to your resource `Sylius\Component\Resource\Factory\Factory`.

It has a `createNew` method with no arguments.

## Inject the factory in your service

If you are using Symfony autowiring, you can inject the resource factory using the right variable name.

```php
namespace App;

use Sylius\Resource\Factory\FactoryInterface;

final class MyService
{
    public function __construct(
        private FactoryInterface $bookFactory,
    ) {}
}
```

In this example, the `app.factory.book` will be injected in your `$bookFactory`

You can find the variable name using this debug command:

```shell
$ bin/console debug:autowiring app.factory.book
```

## Define your custom factory

```php
// src/Factory/BookFactory.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use Sylius\Resource\Factory\FactoryInterface;

final class BookFactory implements FactoryInterface
{
    public function createNew(): Book
    {
        $book = new Book();
        $book->setCreatedAt(new \DateTimeImmutable());
        
        return $book;
    }
}
```

Configure your factory

```yaml
# config/services.yaml
services:
    App\Factory\BookFactory:
        decorates: 'app.factory.book'
```

## Use your custom method

```php
// src/Factory/BookFactory.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use Sylius\Resource\Factory\FactoryInterface;
use Symfony\Component\Security\Core\Security;

final class BookFactory implements FactoryInterface
{
    public function __construct(private Security $security) 
    {
    }

    public function createNew(): Book
    {
        return new Book();
    }
    
    public function createWithCreator(): Book
    {
        $book = $this->createNew();
        
        $book->setCreator($this->security->getUser());
        
        return $book;
    }
}
```

Use it on your create operation

```php
// src/Entity/Book.php

declare(strict_types=1);

namespace App\Entity\Book;

use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Model\ResourceInterface;

#[AsResource]
#[Create(
    path: 'authors/{authorId}/books',
    factoryMethod: 'createWithCreator',
)]
class Book implements ResourceInterface
{
}
```

## Pass arguments to your method

You can pass arguments to your factory method.

3 variables are available:

* `request`: to retrieve data from the request via `Symfony\Component\HttpFoundation\Request`
* `token`: to retrieve data from the authentication token via `Symfony\Component\Security\Core\Authentication\Token\TokenInterface`
* `user`: to retrieve data from the logged-in user via `Symfony\Component\Security\Core\User\UserInterface`

It uses the [Symfony expression language](https://symfony.com/doc/current/components/expression_language.html) component.

```php
// src/Factory/BookFactory.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Factory\FactoryInterface;

final class BookFactory implements FactoryInterface
{
    public function __construct(private RepositoryInterface $authorRepository) 
    {
    }

    public function createNew(): Book
    {
        return new Book();
    }
    
    public function createForAuthor(string $authorId): Book
    {
        $book = $this->createNew();
        
        $author = $this->authorRepository->find($authorId);
        
        $book->setAuthor($author);
        
        return $book;
    }
}
```

Use it on your create operation

```php
// src/Entity/Book.php

declare(strict_types=1);

namespace App\Entity\Book;

use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;

#[AsResource]
#[Create(
    path: 'authors/{authorId}/books',
    factoryMethod: 'createForAuthor',
    factoryArguments: ['authorId' => "request.attributes.get('authorId')"],
)]
class Book implements ResourceInterface
{
}
```

## Use a factory without declaring it 

You can use a factory without declaring it on `services.yaml`.

```php
// src/Entity/Book.php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Factory\BookFactory;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;

#[AsResource]
#[Create(
    path: 'authors/{authorId}/books',
    # Here we declared the factory to use with its fully classified class name
    factory: BookFactory::class,
    factoryMethod: 'createForAuthor', 
    factoryArguments: ['authorId' => "request.attributes.get('authorId')"],
)]
class Book implements ResourceInterface
{
}
```


## Use a callable for your custom factory

```php
// src/Factory/BookFactory.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;

final class BookFactory
{    
    public static function create(): Book
    {
        return new Book();
    }
}
```

```php
// src/Entity/Book.php

declare(strict_types=1);

namespace App\Entity\Book;

use App\Factory\BookFactory;
use Sylius\Resource\Metadata\AsResource;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Model\ResourceInterface

#[AsResource]
#[Create(
    factory: [BookFactory::class, 'create'], 
)]
class Book implements ResourceInterface
{
}
```

**[Go back to the documentation's index](index.md)**

**[> Next chapter: Providers](providers.md)**
