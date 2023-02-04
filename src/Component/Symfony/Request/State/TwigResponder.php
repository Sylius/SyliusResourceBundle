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

namespace Sylius\Component\Resource\Symfony\Request\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\CollectionOperationInterface;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandler;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TwigResponder implements ResponderInterface
{
    public function __construct(
        private RedirectHandler $redirectHandler,
        private ?Environment $twig,
    ) {
    }

    public function respond(mixed $data, Operation $operation, Context $context): ?Response
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $this->twig) {
            throw new \LogicException('You can not use the "Twig" if it is not available. Try running "composer require twig".');
        }

        if (null === $request) {
            return null;
        }

        $isValid = $request->attributes->getBoolean('is_valid', true);

        if ($operation instanceof DeleteOperationInterface && $operation instanceof HttpOperation) {
            return $this->redirectHandler->redirectToResource($data, $operation, $request);
            //return $this->redirectHandler->redirectToIndex($data, $operation, $request);
        }

        if (
            $isValid &&
            $operation instanceof HttpOperation &&
            ($operation instanceof UpdateOperationInterface || $operation instanceof CreateOperationInterface)
        ) {
            return $this->redirectHandler->redirectToResource($data, $operation, $request);
        }

        $content = $this->twig->render(
            $operation->getTemplate() ?? '',
            $this->getTwigContext($data, $operation, $request),
        );

        return new Response($content);
    }

    private function getTwigContext(mixed $data, Operation $operation, Request $request): array
    {
        $context = ['operation' => $operation];

        /** @var FormInterface|null $form */
        $form = $request->attributes->get('form');

        if (null !== $form) {
            $context['form'] = $form->createView();
        }

        if ($operation instanceof CollectionOperationInterface) {
            $context['resources'] = $data;
            $context[$operation->getResource()?->getPluralName() ?? ''] = $data;
        } else {
            $context['resource'] = $data;
            $context[$operation->getResource()?->getName() ?? ''] = $data;
        }

        return $context;
    }
}
