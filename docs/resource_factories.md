# Resource factories

## Default factory for your resource

By default, a resource factory is defined to your resource `Sylius\Component\Resource\Factory\Factory`.

It has 'createNew' method with no arguments.

## Define your custom factory

```php
// src/Factory/BookRepository.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;use Sylius\Component\Resource\Factory\FactoryInterface;

final class BookFactory implements FactoryInterface
{
    public function createNew(): Book
    {
        return new Book();
    }
}
```

Configure your factory

```yaml
services:
    App\Factory\BookFactory:
        decorates: '@.inner'
```

## Use your custom method

```php
// src/Factory/BookRepository.php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Book;use Sylius\Component\Resource\Factory\FactoryInterface;use Sylius\Component\Resource\Repository\RepositoryInterface;

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

Use it on your operation

```php
// src/Entity/Book.php

declare(strict_types=1);

namespace App\Entity\Book;

use Sylius\Component\Resource\Metadata\Create;use Sylius\Component\Resource\Metadata\Resource;use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Create(
    factoryMethod: 'createForAuthor', 
    factoryArguments: ['authorId' => "request.attributes.get('authorId')"],
)]
class Book implements \Sylius\Component\Resource\Model\ResourceInterface
{
}
```
