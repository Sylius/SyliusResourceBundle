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

use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Resource\Util\RequestConfigurationInitiatorTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Twig\Environment;

final class RespondListener
{
    use RequestConfigurationInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private RedirectHandlerInterface $redirectHandler,
        private ?Environment $twig,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();
        $isValid = $request->attributes->get('is_valid', false);
        $data = $request->attributes->get('data');

        if (null === $configuration = $this->initializeConfiguration($request)) {
            return;
        }

        if ($controllerResult instanceof Response && $configuration->canRespond()) {
            $event->setResponse($controllerResult);

            return;
        }

        if ($controllerResult instanceof Response || !$configuration->canRespond()) {
            return;
        }

        if (!$request->isXmlHttpRequest()) {
        }

        if ($isValid) {
            $response = $this->redirectHandler->redirectToResource($configuration, $data);
            $event->setResponse($response);

            return;
        }

        $content = $this->twig->render(
            $configuration->getTemplate($configuration->getOperation()),
            $this->getContext($configuration),
        );

        $response = new Response();
        $response->setContent($content);

        $event->setResponse($response);
    }

    private function getContext(RequestConfiguration $configuration): array
    {
        $request = $configuration->getRequest();
        $data = $request->attributes->get('data');

        /** @var FormInterface|null $form */
        $form = $request->attributes->get('form');

        if (ResourceActions::INDEX === $configuration->getOperation()) {
            return ['resources' => $data];
        }

        if (ResourceActions::SHOW === $configuration->getOperation()) {
            return ['resource' => $data];
        }

        if (in_array($configuration->getOperation(), [ResourceActions::CREATE, ResourceActions::UPDATE], true)) {
            return [
                'resource' => $data,
                'form' => $form?->createView(),
            ];
        }

        return [];
    }
}
