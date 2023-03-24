# Configure your resource

Read the previous chapter to [create a new resource](create_new_resource.md).

<!-- TOC -->
* [Implements the Resource interface](#implements-the-resource-interface)
* [Use the Resource attribute](#use-the-resource-attribute)
* [Configure your operations](#configure-your-operations)
  * [Index operation](#index-operation)
  * [Use a grid to your index operation](#use-a-grid-to-your-index-operation)
  * [Create operation](#create-operation)
  * [Update operation](#update-operation)
  * [Delete operation](#delete-operation)
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
