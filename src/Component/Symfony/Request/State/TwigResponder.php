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
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\DeleteOperationInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Symfony\Routing\RedirectHandler;
use Sylius\Component\Resource\Twig\Context\Factory\ContextFactoryInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class TwigResponder implements ResponderInterface
{
    public function __construct(
        private RedirectHandler $redirectHandler,
        private ContextFactoryInterface $contextFactory,
        private ?Environment $twig,
    ) {
    }

    public function respond(mixed $data, Operation $operation, Context $context): ?Response
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return null;
        }

        // TODO use $request->getRequestFormat();
        $format = $request->attributes->get('_format');

        $isValid = $request->attributes->getBoolean('is_valid', true);

        if (
            'html' === $format &&
            $operation instanceof DeleteOperationInterface
        ) {
            return $this->redirectHandler->redirectToResource($data, $operation, $request);
        }

        if (
            $isValid &&
            'html' === $format &&
            ($operation instanceof UpdateOperationInterface || $operation instanceof CreateOperationInterface)
        ) {
            return $this->redirectHandler->redirectToResource($data, $operation, $request);
        }

        $content = $data;

        if ('html' === $format) {
            if (null === $this->twig) {
                throw new \LogicException('You can not use the "html" format if Twig is not available. Try running "composer require twig".');
            }

            $content = $this->twig->render(
                $operation->getTemplate() ?? '',
                $this->contextFactory->create($data, $operation, $context),
            );
        }

        Assert::string($content);

        if ('html' === $format) {
            return new Response($content, $isValid ? Response::HTTP_OK : Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $status = Response::HTTP_OK;

        if ($operation instanceof CreateOperationInterface) {
            $status = Response::HTTP_CREATED;
        }

        if ($operation instanceof DeleteOperationInterface || $operation instanceof UpdateOperationInterface) {
            $status = Response::HTTP_NO_CONTENT;
        }

        return new Response($content, $isValid ? $status : Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
