# Configure your resource

To create your resource, read the previous chapter [Create a new resource](create_new_resource.md)

<!-- TOC -->
* [Implements the Resource interface](#implements-the-resource-interface)
* [Use the Resource attribute](#use-the-resource-attribute)
* [Configure your operations](#configure-your-operations)
<!-- TOC -->

## Implements the Resource interface

To declare your resource as a Sylius one, you need to implement the ```Sylius\Component\Resource\Model\ResourceInterface``` which requires you to implement a `getId()` method.

```php
declare(strict_types=1);

namespace Sylius\Component\Resource\Model;

interface ResourceInterface
{
    public function getId();
}

```

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

| Name                  | Method          | Path     |
|-----------------------|-----------------|----------|
| app_book_index        | GET             | /books/  |
