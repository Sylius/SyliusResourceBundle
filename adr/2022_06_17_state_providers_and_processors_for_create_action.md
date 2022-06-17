# Using state providers and state processors for create action

## State Providers

* [Getting the configuration](#state-processors)
* [Creating the resource](#creating-the-resource)
* [Dispatching access event](#dispatching-access-event)

## State Processors

* [Handling the request](#handling-the-request)
* [Handling the form](#handling-the-form)
* [Rendering the bad request response](#rendering-the-bad-request-response)

## Getting the configuration
```php
$configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
```

## Creating the resource
```php
$this->isGrantedOr403($configuration, ResourceActions::CREATE); // Could be replace by 
$newResource = $this->newResourceFactory->create($configuration, $this->factory);
```

## Dispatching access event
```php
$event = $this->eventDispatcher->dispatchAccessEvent($configuration, ResourceActions::CREATE, $newResource);
```

Then a developer can use an event subscriber to handle rules for accessing the resource
```php
<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\Forum\Topic;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Webmozart\Assert\Assert;

class GrantAuthorizationOnCreatingTopicSubscriber implements EventSubscriberInterface
{
    public function __construct(private AuthorizationCheckerInterface $authorizationChecker)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            'app.topic.create_access' => 'grantAuthorization',
        ];
    }

    public function grantAuthorization(GenericEvent $event): void
    {
        $topic = $event->getSubject();

        Assert::isInstanceOf($installer, Topic::class);

        if (!$this->authorizationChecker->isGranted('create', $topic)) {
            throw new AccessDeniedException();
        }
    }
}
```

And then use a voter.

```php
<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Forum\Topic;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CreateTopicVoter extends Voter
{
    protected function supports(string $attribute, $subject): bool
    {
        return 'create' === $attribute && $subject instanceof Topic;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        $topic = $subject;

       // implement here your rules.
    }
}

```


## Handling the request

```php
$form = $this->resourceFormFactory->create($configuration, $newResource);
$form->handleRequest($request);
```

## Handling the form

Here we have to split this following huge part on some tiny processors

```php
if ($request->isMethod('POST') && $form->isSubmitted() && $form->isValid()) {
    $newResource = $form->getData();

    $event = $this->eventDispatcher->dispatchPreEvent(ResourceActions::CREATE, $configuration, $newResource);

    if ($event->isStopped() && !$configuration->isHtmlRequest()) {
        throw new HttpException($event->getErrorCode(), $event->getMessage());
    }
    if ($event->isStopped()) {
        $this->flashHelper->addFlashFromEvent($configuration, $event);

        $eventResponse = $event->getResponse();
        if (null !== $eventResponse) {
            return $eventResponse;
        }

        return $this->redirectHandler->redirectToIndex($configuration, $newResource);
    }

    if ($configuration->hasStateMachine()) {
        $stateMachine = $this->getStateMachine();
        $stateMachine->apply($configuration, $newResource);
    }

    $this->repository->add($newResource);

    if ($configuration->isHtmlRequest()) {
        $this->flashHelper->addSuccessFlash($configuration, ResourceActions::CREATE, $newResource);
    }

    $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::CREATE, $configuration, $newResource);

    if (!$configuration->isHtmlRequest()) {
        return $this->createRestView($configuration, $newResource, Response::HTTP_CREATED);
    }

    $postEventResponse = $postEvent->getResponse();
    if (null !== $postEventResponse) {
        return $postEventResponse;
    }

    return $this->redirectHandler->redirectToResource($configuration, $newResource);
}
```

## Rendering the bad request response

```php
if (!$configuration->isHtmlRequest()) {
    return $this->createRestView($configuration, $form, Response::HTTP_BAD_REQUEST);
}

$initializeEvent = $this->eventDispatcher->dispatchInitializeEvent(ResourceActions::CREATE, $configuration, $newResource);
$initializeEventResponse = $initializeEvent->getResponse();
if (null !== $initializeEventResponse) {
    return $initializeEventResponse;
}

return $this->render($configuration->getTemplate(ResourceActions::CREATE . '.html'), [
    'configuration' => $configuration,
    'metadata' => $this->metadata,
    'resource' => $newResource,
    $this->metadata->getName() => $newResource,
    'form' => $form->createView(),
]);
```
