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

namespace Sylius\Component\Resource\Symfony\Request\State;

use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\HttpOperation;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandlerInterface;
use Sylius\Component\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\State\ResponderInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class TwigResponder implements ResponderInterface
{
    public function __construct(
        private RedirectHandlerInterface $redirectHandler,
        private ContextFactoryInterface $contextFactory,
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
            $this->contextFactory->create($data, $operation, $context),
        );

        return new Response($content, $request->isMethodSafe() || $isValid ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
