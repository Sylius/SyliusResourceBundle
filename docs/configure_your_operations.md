# Configure your operations

Read the previous chapter to [configure your resource](configure_your_resource.md).

Now, with your fresh new resource, you have to define the operations that you need to implement.
There are some basic CRUD operations and more. 

<!-- TOC -->
* [Basic operations](#basic-operations)
  * [Index operation](#index-operation)
  * [Use a grid for your index operation](#use-a-grid-for-your-index-operation)
  * [Create operation](#create-operation)
  * [Update operation](#update-operation)
  * [Delete operation](#delete-operation)
  * [State machine operation](#state-machine-operation)
* [Advanced configuration](#advanced-configuration)
  * [Configure the path](#configure-the-path)
  * [Configure the short name](#configure-the-short-name)
  * [Configure the templates' dir](#configure-the-templates-dir)
  * [Configure the section](#configure-the-section)
  * [Configure the routes' prefix](#configure-the-routes-prefix)
  * [Configure the resource identifier](#configure-the-resource-identifier)
  * [Configure the vars](#configure-the-vars)
<!-- TOC -->

## Basic operations

### Index operation

`Index` operation allows to browse all items of your resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Index;

#[AsResource]
#[Index]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `index` operation.

| Name                  | Method          | Path    |
|-----------------------|-----------------|---------|
| app_book_index        | GET             | /books  |

On your Twig template, these variables are available

| Name      | Type                                     |
|-----------|------------------------------------------|
| resources | Pagerfanta\Pagerfanta                    |
| books     | Pagerfanta\Pagerfanta                    |
| operation | Sylius\Component\Resource\Metadata\Index |
| app       | Symfony\Bridge\Twig\AppVariable          |

### Use a grid for your index operation

To use a grid for you operation, you need to install the [Sylius grid package](https://github.com/Sylius/SyliusGridBundle/)

```php
namespace App\Entity;

use App\Grid\BookGrid;use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Index;

#[AsResource]
// You can use either the FQCN of your grid
#[Index(grid: BookGrid::class)]
// Or you can use the grid name
#[Index(grid: 'app_book')]
class Book implements ResourceInterface
{
}

```

On your Twig template, these variables are available

| Name      | Type                                                    |
|-----------|---------------------------------------------------------|
| resources | Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView |
| books     | Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView |
| operation | Sylius\Component\Resource\Metadata\Index                |
| app       | Symfony\Bridge\Twig\AppVariable                         |

The iterator for your books will be available as `books.data` or `resources.data`.

### Create operation

`Create` operation allows to add a new item of your resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Create;

#[AsResource]
#[Create]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `create` operation.

| Name            | Method    | Path       |
|-----------------|-----------|------------|
| app_book_create | GET, POST | /books/new |

On your Twig template, these variables are available

| Name      | Type                                      |
|-----------|-------------------------------------------|
| resource  | App\Entity\Book                           |
| book      | App\Entity\Book                           |
| operation | Sylius\Component\Resource\Metadata\Create |
| app       | Symfony\Bridge\Twig\AppVariable           |

The iterator for your books will be available as `books.data` or `resources.data`.

### Update operation

`Update` operation allows to edit an existing item of your resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Update;

#[AsResource]
#[Update]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `update` operation.

| Name            | Method          | Path             |
|-----------------|-----------------|------------------|
| app_book_update | GET, PUT, PATCH | /books/{id}/edit |

On your Twig template, these variables are available

| Name      | Type                                      |
|-----------|-------------------------------------------|
| resource  | App\Entity\Book                           |
| book      | App\Entity\Book                           |
| operation | Sylius\Component\Resource\Metadata\Update |
| app       | Symfony\Bridge\Twig\AppVariable           |

### Delete operation

`Delete` operation allows to remove an existing item of your resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Delete;

#[AsResource]
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

`Bulk delete` operation allows to remove several items of your resource at the same time.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\BulkDelete;

#[AsResource]
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

`Show` operation allows to view details of an item.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Show;

#[AsResource]
#[Show]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `show` operation.

| Name            | Method | Path        |
|-----------------|--------|-------------|
| app_book_show   | GET    | /books/{id} |    

On your Twig template, these variables are available

| Name      | Type                                    |
|-----------|-----------------------------------------|
| resource  | App\Entity\Book                         |
| book      | App\Entity\Book                         |
| operation | Sylius\Component\Resource\Metadata\Show |
| app       | Symfony\Bridge\Twig\AppVariable         |

### State machine operation


`State machine` operation allows to apply a transition to an item of your resource.

As an example, we add a `publish` operation to our book resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\ApplyStateMachineTransition;use Sylius\Resource\Metadata\AsResource;

#[AsResource]
#[ApplyStateMachineTransition(stateMachineTransition: 'publish')]
class Book implements ResourceInterface
{
}

```

It will configure this route for your `apply_state_machine_transition` operation.

| Name              | Method | Path                |
|-------------------|--------|---------------------|
| app_book_publish  | GET    | /books/{id}/publish |    

## Advanced configuration

### Configure the path

It customizes the path for your operations.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Create;

#[AsResource]
#[Create(path: 'register')]
class Customer implements ResourceInterface
{
}

```

| Name            | Method    | Path                 |
|-----------------|-----------|----------------------|
| app_book_create | GET, POST | /books/{id}/register |    

### Configure the short name

It customizes the path for your operations.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Create;

#[AsResource]
#[Create(shortName: 'register')]
class Customer implements ResourceInterface
{
}

```

| Name              | Method    | Path                 |
|-------------------|-----------|----------------------|
| app_book_register | GET, POST | /books/{id}/register |    

It influences the path by default too, but you can still customize the path if needed.

### Configure the templates' dir

It defines the templates directory for your operations.

As an example, we defines `index`, `create`, `update` and `show` operations to our book resource.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Create;use Sylius\Resource\Metadata\Index;use Sylius\Resource\Metadata\Show;use Sylius\Resource\Metadata\Update;

#[AsResource(templatesDir: 'book')]
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

### Configure the routes' prefix

It adds a prefix to the path for each operation.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\BulkDelete;use Sylius\Resource\Metadata\Create;use Sylius\Resource\Metadata\Delete;use Sylius\Resource\Metadata\Index;use Sylius\Resource\Metadata\Show;use Sylius\Resource\Metadata\Update;

#[AsResource(routePrefix: 'admin')]
#[Index]
#[Create]
#[Update]
#[Delete]
#[BulkDelete]
#[Show]
class Book implements ResourceInterface
{
}

```

| Name                   | Method          | Path                     |
|------------------------|-----------------|--------------------------|
| app_book_index         | GET             | /admin/books/            |
| app_book_create        | GET, POST       | /admin/books/new         |                     
| app_book_update        | GET, PUT, PATCH | /admin/books/{id}/edit   |        
| app_book_delete        | DELETE          | /admin/books/{id}        |
| app_book_bulk_delete   | DELETE          | /admin/books/bulk_delete |               
| app_book_show          | GET             | /admin/books/{id}        |

### Configure the section

It changes the route name for each operation.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\BulkDelete;use Sylius\Resource\Metadata\Create;use Sylius\Resource\Metadata\Delete;use Sylius\Resource\Metadata\Index;use Sylius\Resource\Metadata\Show;use Sylius\Resource\Metadata\Update;

#[AsResource(section: 'admin', routePrefix: 'admin')]
#[Index]
#[Create]
#[Update]
#[Delete]
#[BulkDelete]

#[AsResource(section: 'shop')]
#[Index]
#[Show]
class Book implements ResourceInterface
{
}

```

| Name                       | Method          | Path                     |
|----------------------------|-----------------|--------------------------|
| app_admin_book_index       | GET             | /admin/books/            |
| app_admin_book_create      | GET, POST       | /admin/books/new         |                     
| app_admin_book_update      | GET, PUT, PATCH | /admin/books/{id}/edit   |        
| app_admin_book_delete      | DELETE          | /admin/books/{id}        |
| app_admin_book_bulk_delete | DELETE          | /admin/books/bulk_delete |    
| app_shop_book_index        | GET             | /books/                  |
| app_shop_book_show         | GET             | /books/{id}              |

### Configure the resource identifier

It changes the resource identifier for each operation.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\BulkDelete;use Sylius\Resource\Metadata\Create;use Sylius\Resource\Metadata\Delete;use Sylius\Resource\Metadata\Index;use Sylius\Resource\Metadata\Update;

#[AsResource(identifier: 'code')]
#[Index]
#[Create]
#[Update]
#[Delete]
#[BulkDelete]
class Book implements ResourceInterface
{
}

```

| Name                 | Method          | Path                     |
|----------------------|-----------------|--------------------------|
| app_book_index       | GET             | /admin/books/            |
| app_ook_create       | GET, POST       | /admin/books/new         |                     
| app_book_update      | GET, PUT, PATCH | /admin/books/{code}/edit |        
| app_book_delete      | DELETE          | /admin/books/{code}      |
| app_book_bulk_delete | DELETE          | /admin/books/bulk_delete |

### Configure the vars

It defines the simple vars that you can use on your templates.

```php
namespace App\Entity;

use Sylius\Component\Resource\Model\ResourceInterface;use Sylius\Resource\Metadata\AsResource;use Sylius\Resource\Metadata\Create;

#[AsResource(vars: ['header' => 'Library', 'subheader' => 'Managing your library'])]
#[Create(vars: ['subheader' => 'Adding a book'])]
class Book implements ResourceInterface
{
}

```

You can use these vars on your Twig templates.
These vars will be available on any operations for this resource.

```html
<h1>{{ operation.vars.header }}<!-- Library --></h1>
<h2>{{ operation.vars.subheader }}<!-- Adding a book --></h2>
```

**[Go back to the documentation's index](index.md)**

**[> Next chapter: Redirect](redirect.md)**
