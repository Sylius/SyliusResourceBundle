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
use Sylius\Component\Resource\Metadata\Factory\OperationFactoryInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\ResourceActions;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Sylius\Component\Resource\Util\RequestConfigurationInitiatorTrait;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Twig\Environment;

final class RespondListener
{
    use RequestConfigurationInitiatorTrait;
    use OperationRequestInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private OperationFactoryInterface $operationFactory,
        private RedirectHandlerInterface $redirectHandler,
        private ?Environment $twig,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var Response|ResourceInterface $controllerResult */
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();
        $isValid = $request->attributes->get('is_valid', false);

        if (
            (null === $configuration = $this->initializeConfiguration($request)) ||
            (null === $operation = $this->initializeOperation($request))
        ) {
            return;
        }

        if ($controllerResult instanceof Response && ($operation->canRespond() ?? true)) {
            $event->setResponse($controllerResult);

            return;
        }

        if ($controllerResult instanceof Response || !($operation->canRespond() ?? true)) {
            return;
        }

        if (ResourceActions::DELETE === $operation->getAction()) {
            $response = $this->redirectHandler->redirectToIndex($configuration, $controllerResult);
            $event->setResponse($response);

            return;
        }

        if ($isValid) {
            $response = $this->redirectHandler->redirectToResource($configuration, $controllerResult);
            $event->setResponse($response);

            return;
        }

        $content = $this->twig->render(
            $configuration->getTemplate($operation->getAction()),
            $this->getContext($controllerResult, $operation, $configuration),
        );

        $response = new Response();
        $response->setContent($content);

        $event->setResponse($response);
    }

    private function getContext(object $controllerResult, Operation $operation, RequestConfiguration $configuration): array
    {
        $request = $configuration->getRequest();

        /** @var FormInterface|null $form */
        $form = $request->attributes->get('form');

        $context = [
            'configuration' => $configuration,
            'metadata' => $configuration->getMetadata(),
        ];

        if (ResourceActions::INDEX === $operation->getAction()) {
            $context['resources'] = $controllerResult;

            return $context;
        }

        if (ResourceActions::SHOW === $operation->getAction()) {
            $context['resource'] = $controllerResult;

            return $context;
        }

        if (in_array($operation->getAction(), [ResourceActions::CREATE, ResourceActions::UPDATE], true)) {
            $context['resource'] = $controllerResult;
            $context['form'] = $form?->createView();

            return $context;
        }

        return [];
    }
}
