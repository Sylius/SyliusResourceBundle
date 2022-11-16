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
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
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
        private ResourceMetadataFactoryInterface $resourceMetadataFactory,
        private RedirectHandlerInterface $redirectHandler,
        private ?Environment $twig,
    ) {
    }

    public function onKernelView(ViewEvent $event): void
    {
        /** @var Response|ResourceInterface $controllerResult */
        $controllerResult = $event->getControllerResult();
        $request = $event->getRequest();
        $isValid = $request->attributes->get('is_valid', true);

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

        if ($operation instanceof DeleteOperationInterface) {
            $response = $this->redirectHandler->redirectToIndex($configuration, $controllerResult);
            $event->setResponse($response);

            return;
        }

        if ($isValid && ($operation instanceof UpdateOperationInterface || $operation instanceof CreateOperationInterface)) {
            $response = $this->redirectHandler->redirectToResource($configuration, $controllerResult);
            $event->setResponse($response);

            return;
        }

        $content = $this->twig->render(
            $configuration->getTemplate($operation->getName()),
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

        if ($operation instanceof CollectionOperationInterface) {
            $context['resources'] = $controllerResult;
        } else {
            $context['resource'] = $controllerResult;
        }

        if (null !== $form) {
            $context['form'] = $form->createView();
        }

        return $context;
    }
}
