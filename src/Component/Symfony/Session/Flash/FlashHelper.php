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

namespace Sylius\Component\Resource\Symfony\Session\Flash;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Humanizer\StringHumanizer;
use Sylius\Component\Resource\Metadata\BulkOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Symfony\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Webmozart\Assert\Assert;

final class FlashHelper implements FlashHelperInterface
{
    public function __construct(
        private TranslatorInterface $translator,
    ) {
    }

    public function addSuccessFlash(Operation $operation, Context $context): void
    {
        $this->addFlashFromOperation($operation, $context, 'success');
    }

    public function addFlashFromEvent(GenericEvent $event, Context $context): void
    {
        $message = $this->buildEventMessage($event);

        $this->addFlash($message, $event->getMessageType(), $context);
    }

    private function addFlashFromOperation(Operation $operation, Context $context, string $type): void
    {
        $message = $this->buildOperationMessage($operation, $type);

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

        $key = sprintf('%s.%s.%s', $resource->getApplicationName() ?? '', $resource->getName() ?? '', $operation->getShortName() ?? '');
        $fallbackKey = sprintf('sylius.resource.%s', $operation->getShortName() ?? '');

        $parameters = $this->getTranslationParameters($operation);

        if (!$this->translator instanceof TranslatorBagInterface) {
            return $this->translator->trans($fallbackKey, $parameters, 'flashes');
        }

        if ($this->translator->getCatalogue()->has($key, 'flashes')) {
            return $this->translator->trans($key, $parameters, 'flashes');
        }

        return $this->translator->trans($fallbackKey, $parameters, 'flashes');
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

        $resourceName = $operation instanceof BulkOperationInterface ? $resource->getPluralName() : $resource->getName();
        $humanizedName = ucfirst(StringHumanizer::humanize($resourceName ?? ''));

        if ($operation instanceof BulkOperationInterface) {
            return ['%resources%' => $humanizedName];
        }

        return ['%resource%' => $humanizedName];
    }
}
