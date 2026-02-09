<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Resource\Symfony\Session\Flash;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Humanizer\StringHumanizer;
use Sylius\Resource\Metadata\BulkOperationInterface;
use Sylius\Resource\Metadata\CreateOperationInterface;
use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\UpdateOperationInterface;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

/**
 * @experimental
 */
final class FlashHelper implements FlashHelperInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function addSuccessFlash(Operation $operation, Context $context, ?string $message = null): void
    {
        $this->addFlashFromOperation($operation, $context, 'success', $message);
    }

    public function addErrorFlash(Operation $operation, Context $context, ?string $message = null): void
    {
        $this->addFlashFromOperation($operation, $context, 'error', $message);
    }

    public function addFlashFromEvent(GenericEvent $event, Context $context): void
    {
        $message = $this->buildEventMessage($event);

        $this->addFlash($message, $event->getMessageType(), $context);
    }

    private function addFlashFromOperation(Operation $operation, Context $context, string $type, ?string $message): void
    {
        $message ??= $this->buildOperationMessage($operation, $type);

        $this->addFlash($message, $type, $context);
    }

    private function buildEventMessage(GenericEvent $event): string
    {
        $message = $event->getMessage();
        $parameters = $event->getMessageParameters();

        if (!$this->translator instanceof TranslatorBagInterface) {
            return $this->translator->trans($message, $parameters, 'flashes');
        }

        if ($this->translator->getCatalogue()->has($message, 'flashes')) {
            return $this->translator->trans($message, $parameters, 'flashes');
        }

        return $message;
    }

    private function buildOperationMessage(Operation $operation, string $type): string
    {
        $resource = $operation->getResource();
        Assert::notNull($resource);

        $translationKeys = iterator_to_array($this->getTranslationKeys($resource, $operation, $type));

        /** @var string $firstTranslationKey */
        $firstTranslationKey = reset($translationKeys);

        $parameters = $this->getTranslationParameters($operation);
        $notificationMessage = $operation->getNotificationMessage();

        // It's defined by the user, it should be used.
        if ('success' === $type && null !== $notificationMessage) {
            // Do not use the translator if not needed
            if ($this->translator instanceof TranslatorBagInterface && !$this->translator->getCatalogue()->has($notificationMessage, 'flashes')) {
                return $notificationMessage;
            }

            return $this->translator->trans($notificationMessage, $parameters, 'flashes');
        }

        if (!$this->translator instanceof TranslatorBagInterface) {
            return $this->translator->trans($firstTranslationKey, $parameters, 'flashes');
        }

        foreach ($translationKeys as $translationKey) {
            if ($this->translator->getCatalogue()->has($translationKey, 'flashes')) {
                return $this->translator->trans($translationKey, $parameters, 'flashes');
            }
        }

        // Last fallback, use the first translation key.
        return $this->translator->trans($firstTranslationKey, $parameters, 'flashes');
    }

    private function addFlash(string $message, string $type, Context $context): void
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return;
        }

        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');

        $flashBag->add($type, $message);
    }

    private function getTranslationParameters(Operation $operation): array
    {
        $resource = $operation->getResource();

        if (null === $resource) {
            return [];
        }

        $resourceName = $this->translateResource($resource);
        $humanizedName = $resourceName ?? ucfirst(StringHumanizer::humanize($resource->getName() ?? ''));

        if ($operation instanceof BulkOperationInterface) {
            $resourcePluralName = $this->translateResource($resource, true);
            $humanizedPluralName = $resourcePluralName ?? ucfirst(StringHumanizer::humanize($resource->getPluralName() ?? ''));

            return [
                '%resource%' => $humanizedName,
                '%resources%' => $humanizedPluralName,
            ];
        }

        return ['%resource%' => $humanizedName];
    }

    private function translateResource(ResourceMetadata $resource, bool $plurialize = false): ?string
    {
        $translationKey = sprintf(
            '%s.ui.%s',
            $resource->getApplicationName() ?? '',
            $plurialize ? ($resource->getPluralName() ?? '') : ($resource->getName() ?? ''),
        );

        if ($this->translator instanceof TranslatorBagInterface && $this->translator->getCatalogue()->has($translationKey)) {
            return $this->translator->trans($translationKey);
        }

        return null;
    }

    /**
     * @return iterable<string>
     */
    private function getTranslationKeys(ResourceMetadata $resource, Operation $operation, string $type): iterable
    {
        $applicationName = $resource->getApplicationName() ?? '';
        $resourceName = $resource->getName() ?? '';
        $operationShortName = $operation->getShortName() ?? '';

        $translationKeySuffix = 'error' === $type ? '_error' : '';

        /**
         * Examples:
         * app.product.my_operation
         * app.product.my_operation_error
         */
        yield sprintf(
            '%s.%s.%s%s',
            $applicationName,
            $resourceName,
            $operationShortName,
            $translationKeySuffix,
        );

        $genericOperationType = $this->getGenericOperationType($operation);

        /**
         * Examples:
         * app.product.delete
         * app.product.delete_error
         */
        if ($genericOperationType !== $operationShortName) {
            yield sprintf(
                '%s.%s.%s%s',
                $applicationName,
                $resourceName,
                $genericOperationType,
                $translationKeySuffix,
            );
        }

        /**
         * Examples:
         * sylius.resource.my_operation
         * sylius.resource.my_operation_error
         */
        yield sprintf(
            'sylius.resource.%s%s',
            $operationShortName,
            $translationKeySuffix,
        );

        /**
         * Examples:
         * sylius.resource.delete
         * sylius.resource.delete_error
         */
        if ($genericOperationType !== $operationShortName) {
            yield sprintf(
                'sylius.resource.%s%s',
                $genericOperationType,
                $translationKeySuffix,
            );
        }
    }

    private function getGenericOperationType(Operation $operation): ?string
    {
        if ($operation instanceof DeleteOperationInterface) {
            return 'delete';
        }

        if ($operation instanceof CreateOperationInterface) {
            return 'create';
        }

        if ($operation instanceof UpdateOperationInterface) {
            return 'update';
        }

        return null;
    }
}
