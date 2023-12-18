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

namespace Sylius\Resource\State\Processor;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\Symfony\Session\Flash\FlashHelperInterface;
use Symfony\Component\HttpFoundation\Response;

final class FlashProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private FlashHelperInterface $flashHelper,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        $request = $context->get(RequestOption::class)?->request();

        if (null === $request) {
            return $this->processor->process($data, $operation, $context);
        }

        $format = $request->getRequestFormat();

        if (
            $data instanceof Response ||
            $request->isMethodSafe() ||
            $format !== 'html' ||
            !($operation->canWrite() ?? true)
        ) {
            return $this->processor->process($data, $operation, $context);
        }

        $this->flashHelper->addSuccessFlash($operation, $context);

        return $this->processor->process($data, $operation, $context);
    }
}
