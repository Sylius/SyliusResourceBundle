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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashHelper implements FlashHelperInterface
{
    /** @var RequestStack|SessionInterface */
    private $requestStack;

    private TranslatorInterface $translator;

    private string $defaultLocale;

    /**
     * @param RequestStack|SessionInterface $requestStack
     */
    public function __construct(/* RequestStack */ $requestStack, TranslatorInterface $translator, string $defaultLocale)
    {
        /** @phpstan-ignore-next-line */
        if (!$requestStack instanceof SessionInterface && !$requestStack instanceof RequestStack) {
            throw new \InvalidArgumentException(sprintf('The first argument of "%s" should be instance of "%s" or "%s"', __METHOD__, SessionInterface::class, RequestStack::class));
        }

        if ($requestStack instanceof SessionInterface) {
            @trigger_error(sprintf('Passing an instance of %s as constructor argument for %s is deprecated as of Sylius 1.9 and will be removed in 2.0. Pass an instance of %s instead.', SessionInterface::class, self::class, RequestStack::class), \E_USER_DEPRECATED);
        }

        $this->requestStack = $requestStack;
        $this->translator = $translator;
        $this->defaultLocale = $defaultLocale;
    }

    public function addSuccessFlash(
        RequestConfiguration $requestConfiguration,
        string $actionName,
        ?ResourceInterface $resource = null
    ): void {
        $this->addFlashWithType($requestConfiguration, $actionName, 'success');
    }

    public function addErrorFlash(RequestConfiguration $requestConfiguration, string $actionName): void
    {
        $this->addFlashWithType($requestConfiguration, $actionName, 'error');
    }

    public function addFlashFromEvent(RequestConfiguration $requestConfiguration, ResourceControllerEvent $event): void
    {
        $this->addFlash($event->getMessageType(), $event->getMessage(), $event->getMessageParameters());
    }

    private function addFlashWithType(RequestConfiguration $requestConfiguration, string $actionName, string $type): void
    {
        $metadata = $requestConfiguration->getMetadata();
        $parameters = $this->getParametersWithName($metadata, $actionName);

        $message = (string) $requestConfiguration->getFlashMessage($actionName);
        if (empty($message)) {
            return;
        }

        if ($this->isTranslationDefined($message, $this->defaultLocale, $parameters)) {
            if (!$this->translator instanceof TranslatorBagInterface) {
                $this->addFlash($type, $message, $parameters);

                return;
            }

            $this->addFlash($type, $message);

            return;
        }

        $this->addFlash(
            $type,
            $this->getResourceMessage($actionName),
            $parameters
        );
    }

    private function addFlash(string $type, string $message, array $parameters = []): void
    {
        if (!empty($parameters)) {
            $message = $this->prepareMessage($message, $parameters);
        }

        if ($this->requestStack instanceof SessionInterface) {
            $session = $this->requestStack;
        } else {
            $session = $this->requestStack->getSession();
        }

        /** @var FlashBagInterface $flashBag */
        $flashBag = $session->getBag('flashes');
        $flashBag->add($type, $message);
    }

    private function prepareMessage(string $message, array $parameters): array
    {
        return [
            'message' => $message,
            'parameters' => $parameters,
        ];
    }

    private function getResourceMessage(string $actionName): string
    {
        return sprintf('sylius.resource.%s', $actionName);
    }

    private function isTranslationDefined(string $message, string $locale, array $parameters): bool
    {
        if ($this->translator instanceof TranslatorBagInterface) {
            $defaultCatalogue = $this->translator->getCatalogue($locale);

            return $defaultCatalogue->has($message, 'flashes');
        }

        return $message !== $this->translator->trans($message, $parameters, 'flashes');
    }

    private function getParametersWithName(MetadataInterface $metadata, string $actionName): array
    {
        if (stripos($actionName, 'bulk') !== false) {
            return ['%resources%' => ucfirst($metadata->getPluralName())];
        }

        return ['%resource%' => ucfirst($metadata->getHumanizedName())];
    }
}
