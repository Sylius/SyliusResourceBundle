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

namespace Sylius\Resource\State\Provider;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\DeleteOperationInterface;
use Sylius\Resource\Metadata\HttpOperation;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProviderInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

final class DeserializeProvider implements ProviderInterface
{
    public function __construct(
        private ProviderInterface $decorated,
        private ?SerializerInterface $serializer,
    ) {
    }

    public function provide(Operation $operation, Context $context): object|array|null
    {
        $data = $this->decorated->provide($operation, $context);

        if (!$operation instanceof HttpOperation) {
            return $data;
        }

        $request = $context->get(RequestOption::class)?->request() ?? null;
        if (!$request) {
            return $data;
        }

        if (!($operation->canDeserialize() ?? true)) {
            return $data;
        }

        $resourceClass = $operation->getResource()?->getClass();
        /** @var string $format */
        $format = $request->getRequestFormat();

        if (
            null === $resourceClass ||
            'html' === $format ||
            $request->isMethodSafe() ||
            $operation instanceof DeleteOperationInterface
        ) {
            return $data;
        }

        if (null === $this->serializer) {
            throw new \LogicException(sprintf('You can not use the "%s" format if the Serializer is not available. Try running "composer require symfony/serializer".', $format));
        }

        $denormalizationContext = $operation->getDenormalizationContext() ?? [];
        $method = $request->getMethod();

        if (null !== $data && in_array($method, ['POST', 'PATCH', 'PUT'])) {
            $denormalizationContext[AbstractNormalizer::OBJECT_TO_POPULATE] = $data;
        }

        return $this->serializer->deserialize($request->getContent(), $resourceClass, $format, $denormalizationContext);
    }
}
