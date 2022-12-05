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

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\CreateOperationInterface;
use Sylius\Component\Resource\Metadata\Factory\ResourceMetadataFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\State\ProviderInterface;
use Sylius\Component\Resource\Util\ContextInitiatorTrait;
use Sylius\Component\Resource\Util\OperationRequestInitiatorTrait;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class ReadListener
{
    use ContextInitiatorTrait;
    use OperationRequestInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ResourceMetadataFactoryInterface $resourceMetadataFactory,
        private ProviderInterface $provider,
    ) {
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $context = $this->initializeContext($request);

        if (
            (null === $configuration = $context->get(RequestConfiguration::class)) ||
            (null === $operation = $this->initializeOperation($request)) ||
            !($operation->canRead() ?? true) ||
            $operation instanceof CreateOperationInterface
        ) {
            return;
        }

        $data = $this->provider->provide($operation, $configuration);

        if (null === $data) {
            throw new NotFoundHttpException('Not Found');
        }

        $request->attributes->set('data', $data);
    }
}
