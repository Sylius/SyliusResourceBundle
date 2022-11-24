# Kernel events

* Status: new
* Deciders: @lchrusciel, @loic425, @Zales0123
* External advices: @soyuka, @dunglas, @mtarld

## Context and Problem Statement

ResourceController has all the actions, a lot of pretty similar code on each action and hard to maintain/test.
and it is not flexible enough in its behavior.

## Considered Options

* Use Kernel events
* Use one controller per action

## PreWriteListener

### With kernel events

```php
$resourceEvent = $this->eventDispatcher->dispatchPreEvent($operation->getName(), $configuration, $controllerResult);
$request->attributes->set('resource_event', $resourceEvent);

if (!$resourceEvent->isStopped()) {
    return;
}

if (null !== $resourceEventResponse = $resourceEvent->getResponse()) {
    $event->setControllerResult($resourceEventResponse);

    return;
}

if ($operation instanceof CreateOperationInterface) {
    $event->setControllerResult($this->redirectHandler->redirectToIndex($configuration, $controllerResult));

    return;
}

$event->setControllerResult($this->redirectHandler->redirectToResource($configuration, $controllerResult));
```

Moreover, it allows to use a custom controller free of dependencies injection.

```php
#[Resource(alias: 'app.book')]
#[Create(
    factory: false, // to disable resource factory 
    controller: CreateBookAction::class, 
    template: 'book/create.html.twig',
)]
class Book implements ResourceInterface
```

```php
class CreateBookAction
{
    public function __invoke(#[CurrentUser] $user): Book
    {
        return (new Book())->setCreatedBy($user);
    }
}
```

For end-user, he could also disable the existing listeners and build its own way to implement it.

### With controllers

With controllers, to have a quick look for all these little differences, we have to create a new service with this code and it creates another service injected in our controller.
