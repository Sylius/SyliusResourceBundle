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

use Sylius\Bundle\ResourceBundle\Controller\RequestConfigurationFactoryInterface;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Util\RequestConfigurationInitiatorTrait;
use Sylius\Component\Resource\State\ProcessorInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class WriteListener
{
    use RequestConfigurationInitiatorTrait;

    public function __construct(
        private RegistryInterface $resourceRegistry,
        private RequestConfigurationFactoryInterface $requestConfigurationFactory,
        private ProcessorInterface $processor,
    ) {
    }

    public function onKernelView(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (null === $configuration = $this->initializeConfiguration($request)) {
            return;
        }

        if (null === $data = $request->attributes->get('data')) {
            return;
        }

        if (!$request->attributes->get('is_valid')) {
            return;
        }

        $data = $this->processor->process($data, $configuration);
        $request->attributes->set('data', $data);
    }
}
