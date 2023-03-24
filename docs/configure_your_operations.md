# Configure your operations

Read the previous chapter to [configure your resource](configure_your_resource.md).

<!-- TOC -->
* [Index operation](#index-operation)
* [Use a grid for your index operation](#use-a-grid-for-your-index-operation)
* [Create operation](#create-operation)
* [Update operation](#update-operation)
* [Delete operation](#delete-operation)
* [State machine operation](#state-machine-operation)
* [Configure the templates' dir](#configure-the-templates-dir)
<!-- TOC -->

## Index operation

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Index]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `index` operation.

| Name                  | Method          | Path    |
|-----------------------|-----------------|---------|
| app_book_index        | GET             | /books  |

### Use a grid for your index operation

To use a grid for you operation, you need to install the [Sylius grid package](https://github.com/Sylius/SyliusGridBundle/)

```php
namespace App\Entity;

use App\Grid\BookGrid;
use Sylius\Component\Resource\Metadata\Index;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
// You can use either the FQCN of your grid
#[Index(grid: BookGrid::class)]
// Or you can use the grid name
#[Index(grid: 'app_book')]
class Book implements ResourceInterface
{
}

```

## Create operation

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Create]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `create` operation.

| Name            | Method    | Path       |
|-----------------|-----------|------------|
| app_book_create | GET, POST | /books/new |

## Update operation

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Update;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Update]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `update` operation.

| Name            | Method          | Path             |
|-----------------|-----------------|------------------|
| app_book_update | GET, PUT, PATCH | /books/{id}/edit |

## Delete operation

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Delete;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Delete]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `delete` operation.

| Name            | Method | Path        |
|-----------------|--------|-------------|
| app_book_delete | DELETE | /books/{id} |

## Bulk delete operation

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[BulkDelete]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `bulk_delete` operation.

| Name                 | Method | Path               |
|----------------------|--------|--------------------|
| app_book_bulk_delete | DELETE | /books/bulk_delete |    

## Show operation

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Show]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `show` operation.

| Name            | Method | Path        |
|-----------------|--------|-------------|
| app_book_show   | GET    | /books/{id} |    

## State machine operation

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\ApplyStateMachineTransition;use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[ApplyStateMachineTransition(stateMachineTransition: 'publish')]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `apply_state_machine_transition` operation.

| Name              | Method | Path                |
|-------------------|--------|---------------------|
| app_book_publish  | GET    | /books/{id}/publish |    

## Configure the templates' dir

It defines the templates directory for your operations.

As an example, we defines `index`, `create`, `update` and `show` operations to our book resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Create;use Sylius\Component\Resource\Metadata\Index;use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;use Sylius\Component\Resource\Metadata\Update;use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource(templatesDir: 'book')]
#[Index]
#[Create]
#[Update]
#[Show]
class Book implements ResourceInterface
{
}

```

| Operation | Template Path                    |
|-----------|----------------------------------|
| index     | templates/books/index.html.twig  |  
| create    | templates/books/create.html.twig |   
| update    | templates/books/update.html.twig |   
| show      | templates/books/show.html.twig   |
