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

namespace Sylius\Bundle\ResourceBundle\EventListener;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Bundle\ResourceBundle\Form\Factory\FormFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Resource\Util\RequestConfigurationInitiatorTrait;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class DeserializeListener
{
    use RequestConfigurationInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private FormFactoryInterface $formFactory,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();

        if (
            (null === $configuration = $this->initializeConfiguration($request)) ||
            $controllerResult instanceof Response ||
            !in_array($configuration->getOperation(), [ResourceActions::CREATE, ResourceActions::UPDATE], true)
        ) {
            return;
        }

        $form = $this->formFactory->create($configuration, $controllerResult);
        $form->handleRequest($request);

        $request->attributes->set('form', $form);
    }
}
