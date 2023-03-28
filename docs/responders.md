# Responders

Responders respond data: transform data to a Symfony response, return a success in a CLI operation.

<!-- TOC -->
* [Default responders](#default-responders)
* [Customize twig template variables](#customize-twig-template-variables)
<!-- TOC -->

## Default responders

When your operation is an instance of `Sylius\Component\Resource\Metadata\HttpOperation` two responders are configured by default.

The responder will automatically choose the responder depending on the request format:

| Request format | Responder                                                     |
|----------------|---------------------------------------------------------------|
| html           | Sylius\Component\Resource\Symfony\Request\State\TwigResponder |
| json           | Sylius\Component\Resource\Symfony\Request\State\ApiResponder  |
| xml            | Sylius\Component\Resource\Doctrine\Common\State\ApiResponder  |

## Customize Twig template variables

```php
// src/Twig/Context/Factory/ShowSubscriptionContextFactory.php

namespace App\Twig\Context\Factory;

use phpDocumentor\Reflection\Types\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Twig\Context\Factory\ContextFactoryInterface;

final class ShowSubscriptionContextFactory implements ContextFactoryInterface
{
    public function __construct(private ContextFactoryInterface $decorated)
    {
    }

    public function create(mixed $data, Operation $operation, Context $context): array
    {
        return array_merge($this->decorated->create($data, $operation, $context), [
            'foo' => 'bar',
        ]);
    }
}
```

Use it on your operation.

```php
// src/Entity/Subscription.php

namespace App\Entity;

use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Metadata\Show;
use Sylius\Component\Resource\Model\ResourceInterface;

#[Resource]
#[Show(
    template: 'subscription/show.html.twig',
    twigContextFactory: ShowSubscriptionContextFactory::class,
)]
class Subscription implements ResourceInterface
{
}
```

**[Go back to the documentation's index](index.md)**
