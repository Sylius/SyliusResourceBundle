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
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class ApiResponder implements ResponderInterface
{
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

        $headers = [
            'Content-Type' => sprintf('%s; charset=utf-8', $mimeType),
            'Vary' => 'Accept',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'deny',
        ];

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
