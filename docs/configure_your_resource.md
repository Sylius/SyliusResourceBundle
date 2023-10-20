# Configure your resource

Read the previous chapter to [create a new resource](create_new_resource.md).

<!-- TOC -->
* [Implements the Resource interface](#implements-the-resource-interface)
* [Use the Resource attribute](#use-the-resource-attribute)
* [Advanced configuration](#advanced-configuration)
  * [Configure the resource name](#configure-the-resource-name)
  * [Configure the resource plural name](#configure-the-resource-plural-name)
  * [Configure the resource vars](#configure-the-resource-vars)
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

use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ResourceMetadata]
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

use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ResourceMetadata(name: 'cart')]
class Order implements ResourceInterface
{
}

```

On your Twig templates, the `order` variable will be replaced by the `cart` one.

As an example, on a `show` operation following Twig variables will be available:

| Name      | Type                                    |
|-----------|-----------------------------------------|
| resource  | App\Entity\Order                        |
| cart      | App\Entity\Order                        |
| operation | Sylius\Component\Resource\Metadata\Show |
| app       | Symfony\Bridge\Twig\AppVariable         |

### Configure the resource plural name

It defines the resource plural name.

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ResourceMetadata(pluralName: 'library')]
class Book implements ResourceInterface
{
}

```

On your Twig templates, the `books` variable will be replaced by the `library` one.

As an example, on an `index` operation these Twig variables will be available:

| Name      | Type                                     |
|-----------|------------------------------------------|
| resource  | App\Entity\Book                          |
| library   | App\Entity\Book                          |
| operation | Sylius\Component\Resource\Metadata\Index |
| app       | Symfony\Bridge\Twig\AppVariable          |

### Configure the resource vars

It defines the simple vars that you can use on your templates.

```php
namespace App\Entity;

use Sylius\Component\Resource\Metadata\ResourceMetadata;
use Sylius\Component\Resource\Model\ResourceInterface;

#[ResourceMetadata(vars: ['header' => 'Library', 'subheader' => 'Managing your library'])]
class Book implements ResourceInterface
{
}

```

You can use these vars on your Twig templates.
These vars will be available on any operations for this resource.

```html
<h1>{{ operation.vars.header }}</h1>
<h2>{{ operation.vars.subheader }}</h2>
```

**[Go back to the documentation's index](index.md)**

**[> Next chapter: Configure your operations](configure_your_operations.md)**
