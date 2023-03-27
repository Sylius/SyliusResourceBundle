# Resource factories

Resource factories are used on Create operations to instantiate your resource.

<!-- TOC -->
* [Default factory for you resource](#default-factory-for-your-resource)
* [Define your custom factory](#define-your-custom-factory)
* [Use your custom method](#use-your-custom-method)
* [Pass arguments to your method](#pass-arguments-to-your-method)
* [Use a factory without declaring it](#use-a-factory-without-declaring-it)
* [Use a callable for your custom factory](#use-a-callable-for-your-custom-factory)
<!-- TOC -->


## Default factory for your resource

By default, a resource factory is defined to your resource `Sylius\Component\Resource\Factory\Factory`.

It has a `createNew` method with no arguments.

## Define your custom factory

```php
// src/Factory/BookRepository.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use Sylius\Component\Resource\Factory\FactoryInterface;

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
        decorates: '@.inner'
```

## Use your custom method

```php
// src/Factory/BookRepository.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use Symfony\Component\Security\Core\Security;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

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

use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
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
// src/Factory/BookRepository.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

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

use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
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

use App\Factory\BookFactory;use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
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
// src/Factory/BookRepository.php

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

use App\Entity\Book;
use App\Factory\BookFactory;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Create(
    factory: [BookFactory::class, 'create'], 
)]
class Book implements \Sylius\Component\Resource\Model\ResourceInterface
{
}
```

**[Go back to the documentation's index](index.md)**
