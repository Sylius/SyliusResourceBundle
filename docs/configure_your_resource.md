# Configure your resource

Read the previous chapter to [create a new resource](create_new_resource.md).

<!-- TOC -->
* [Implements the Resource interface](#implements-the-resource-interface)
* [Use the Resource attribute](#use-the-resource-attribute)
* [Advanced configuration](#advanced-configuration)
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

## Advanced configuration

### Configure the resource name

It defines the resource name.

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource(name: 'cart')]
class Order implements ResourceInterface
{
}

```

On your Twig templates, the `order` variable will be replaced by the `cart` one.

As an example, on a `show` operation these Twig variables wille be available:

| Name      | Type                                    |
|-----------|-----------------------------------------|
| resource  | App\Entity\Order                        |
| cart      | App\Entity\Order                        |
| operation | Sylius\Component\Resource\Metadata\Show |
| app       | Symfony\Bridge\Twig\AppVariable         |
