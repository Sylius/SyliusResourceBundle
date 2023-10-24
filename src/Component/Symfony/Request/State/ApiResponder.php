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
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\UpdateOperationInterface;
use Sylius\Component\Resource\State\ResponderInterface;
use Sylius\Component\Resource\Symfony\Response\HeadersInitiatorInterface;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class ApiResponder implements ResponderInterface
{
    public function __construct(private HeadersInitiatorInterface $headersInitializer)
    {
    }

    public function respond(mixed $data, Operation $operation, Context $context): ?Response
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return null;
        }

        $isValid = $request->attributes->getBoolean('is_valid', true);

        Assert::string($data, 'Data are not serialized but it should.');

        /** @var string $format */
        $format = $request->getRequestFormat();

        /** @var string $mimeType */
        $mimeType = $request->getMimeType($format);

        $headers = $this->headersInitializer->initializeHeaders($mimeType);

        $status = Response::HTTP_OK;

        if ($operation instanceof CreateOperationInterface) {
            $status = Response::HTTP_CREATED;
        }

        if ($operation instanceof DeleteOperationInterface || $operation instanceof UpdateOperationInterface) {
            $status = Response::HTTP_NO_CONTENT;
        }

        return new Response($data, $isValid ? $status : Response::HTTP_UNPROCESSABLE_ENTITY, $headers);
    }
}
