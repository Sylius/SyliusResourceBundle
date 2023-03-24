# Configure your operations

Read the previous chapter to [configure your resource](configure_your_resource.md).

<!-- TOC -->
* [Index operation](#index-operation)
* [Use a grid to your index operation](#use-a-grid-to-your-index-operation)
* [Create operation](#create-operation)
* [Update operation](#update-operation)
* [Delete operation](#delete-operation)
* [State machine operation](#state-machine-operation)
<!-- TOC -->

## Implements the Resource interface

To declare your resource as a Sylius one, you need to implement the ```Sylius\Component\Resource\Model\ResourceInterface``` which requires you to implement a `getId()` method.

```php
// src/Entity/Book.php

namespace App\Entity;

class Book implements ResourceInterface
{
    public function getId(): int
    {
        return $this->id;
    }
}

```

## Use the Resource attribute

We add the PHP attribute ```#[Resource]``` to the Doctrine entity.
It will configure your entity as a Sylius resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
class Book implements ResourceInterface
{
}

```

```shell
$ bin/console sylius:debug:resource 'App\Entity\book'
```

```
+--------------------+------------------------------------------------------------+
| name               | book                                                       |
| application        | app                                                        |
| driver             | doctrine/orm                                               |
| classes.model      | App\Entity\Book                                            |
| classes.controller | Sylius\Bundle\ResourceBundle\Controller\ResourceController |
| classes.factory    | Sylius\Component\Resource\Factory\Factory                  |
| classes.form       | Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType |
+--------------------+------------------------------------------------------------+
```

By default, it will have the `app.book` alias in Sylius resource which is a concatenation of the application name and the resource name `{application}.{name}`.

## Configure your operations

### Index operation

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

### Use a grid to your index operation

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

### Create operation

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

### Update operation

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

### Delete operation

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

### Bulk delete operation

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

### Show operation

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

### State machine operation

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
