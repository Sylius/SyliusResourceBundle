# Create a new resource

As an example, let's create a Book entity:

<!-- TOC -->
* [Create the entity](#create-the-entity)
* [Configure the BookRepository](#configure-the-bookrepository)
<!-- TOC -->

## Create the entity

To create a new entity, we need to run the following command:

```shell
$ bin/console make:entity 'App\Entity\Book'
```

This command is interactive: it will guide you through the process of adding all the fields you need.
Use the following answers (most of them are the defaults, so you can hit the "Enter" key to use them):

|   Name | Author | Description | Price |
|-------:|--------|:-----------:|:------|
| String | String |    Text     | Float |
|    255 | 255    |      /      |  /    |
|     No | No     |     No      | No    |

****

Here is the full output when running the command:

```
created: src/Entity/Book.php
created: src/Repository/BookRepository.php

Entity generated! Now let's add some fields!
You can always add more fields later manually or by re-running this command.

New property name (press <return> to stop adding fields):
> name

Field type (enter ? to see all types) [string]:
> 

Field length [255]:
>

Can this field be null in the database (nullable) (yes/no) [no]:
> 

updated: src/Entity/Book.php

Add another property? Enter the property name (or press <return> to stop adding fields):
> author

Field type (enter ? to see all types) [string]:
> 

Field length [255]:
>

Can this field be null in the database (nullable) (yes/no) [no]:
> 

updated: src/Entity/Book.php

Add another property? Enter the property name (or press <return> to stop adding fields):
> description

Field type (enter ? to see all types) [string]:
> text

Can this field be null in the database (nullable) (yes/no) [no]:
> 

updated: src/Entity/Book.php

Add another property? Enter the property name (or press <return> to stop adding fields):
> price

Field type (enter ? to see all types) [string]:
> float

Can this field be null in the database (nullable) (yes/no) [no]:
> 

updated: src/Entity/Book.php

Add another property? Enter the property name (or press <return> to stop adding fields):
> 


       
Success! 
       

Next: When you're ready, create a migration with php bin/console make:migration

```

## Configure the BookRepository

The command also generated a Doctrine repository class: `App\Repository\BookRepository`.

```php
<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * [...]
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
    
    // [...]
}
```

The generated code is not compatible with Sylius Resource yet, so we need to make few changes.

* First, your repository should implement the `Sylius\Component\Resource\Repository\RepositoryInterface` interface
* Then, add the `Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait` trait

Your repository should look like this:

```php
<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\ResourceRepositoryTrait;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;

/**
 * @extends ServiceEntityRepository<Book>
 *
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }
}
```

**[Go back to the documentation's index](index.md)**

**[> Next chapter: Configure your resource](configure_your_resource.md)**
