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

namespace Sylius\Component\Resource\Symfony\HttpFoundation\State;

use Sylius\Bundle\ResourceBundle\Controller\RedirectHandlerInterface;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestConfigurationOption;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Symfony\HttpFoundation\Request\RequestConfiguration;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class Responder implements ResponderInterface
{
    public function __construct(
        private RedirectHandlerInterface $redirectHandler,
        private ?Environment $twig,
    ) {
    }

    public function respond(mixed $data, Operation $operation, Context $context): Response
    {
        $requestOption = $context->get(RequestOption::class);
        Assert::notNull($requestOption);

        $requestConfigurationOption = $context->get(RequestConfigurationOption::class);
        Assert::notNull($requestConfigurationOption);

        $configuration = $requestConfigurationOption->configuration();
        $isValid = $requestOption->request()->attributes->get('is_valid', true);

        if ($data instanceof Response && ($operation->canRespond() ?? true)) {
            return $data;
        }

        if ($operation instanceof DeleteOperationInterface) {
            return $this->redirectHandler->redirectToIndex($configuration, $data);
        }

        if ($isValid && ($operation instanceof UpdateOperationInterface || $operation instanceof CreateOperationInterface)) {
            return $this->redirectHandler->redirectToResource($configuration, $data);
        }

        $content = $this->twig->render(
            $configuration->getTemplate($operation->getName()),
            $this->getContext($data, $operation, $configuration),
        );

        return new Response($content);
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
