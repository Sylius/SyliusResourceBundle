# Kernel events

* Status: new
* Deciders: @lchrusciel, @loic425, @Zales0123, @soyuka, @dunglas

## Context and Problem Statement

ResourceController has all the actions, a lot of pretty similar code on each action and hard to maintain/test.

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

### With controllers

With controllers, to have a quick look for all these little differences, we have to create a new service with this code and it creates another service injected in our controller.
