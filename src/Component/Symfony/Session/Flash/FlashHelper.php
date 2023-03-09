<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Component\Resource\Symfony\Session\Flash;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Humanizer\StringHumanizer;
use Sylius\Component\Resource\Metadata\Operation;
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
        $this->addFlash($operation, $context, 'success');
    }

    private function addFlash(Operation $operation, Context $context, string $type): void
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return;
        }

        $message = $this->buildMessage($operation, $type);

        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');

        $flashBag->add($type, $message);
    }

    private function buildMessage(Operation $operation, string $type): string
    {
        $resource = $operation->getResource();
        Assert::notNull($resource);

        $keys = [
            sprintf('%s.%s.%s', $resource->getApplicationName() ?? '', $resource->getName() ?? '', $operation->getShortName() ?? ''),
            sprintf('sylius.resource.%s', $operation->getShortName() ?? ''),
        ];

        $specifyKey = sprintf('%s.%s.%s', $resource->getApplicationName() ?? '', $resource->getName() ?? '', $operation->getShortName() ?? '');
        $defaultKey = sprintf('sylius.resource.%s', $operation->getShortName() ?? '');

        $parameters = $this->getTranslationParameters($operation);

        if (!$this->translator instanceof TranslatorBagInterface) {
            return $this->translator->trans($defaultKey, $parameters, 'flashes');
        }

        if ($this->translator->getCatalogue()->has($specifyKey, 'flashes')) {
            return $this->translator->trans($specifyKey, $parameters, 'flashes');
        }

        return $this->translator->trans($defaultKey, $parameters, 'flashes');
    }

    private function getTranslationParameters(Operation $operation): array
    {
        $resource = $operation->getResource();

        if (null === $resource) {
            return [];
        }

        // TODO plural name with Bulk operation
//        if ($operation instanceof BulkOperationInterface) {
//            return ['%resources%' => ucfirst($resource->getPluralName() ?? '')];
//        }

        return ['%resource%' => ucfirst(StringHumanizer::humanize($resource->getName() ?? ''))];
    }
}
