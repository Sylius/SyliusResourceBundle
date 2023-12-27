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

namespace Sylius\Component\Resource\src\State\Processor;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\State\ProcessorInterface;
use Symfony\Component\HttpFoundation\Response;

final class WriteProcessor implements ProcessorInterface
{
    public function __construct(
        private ProcessorInterface $processor,
        private ProcessorInterface $locatorProcessor,
    ) {
    }

    public function process(mixed $data, Operation $operation, Context $context): mixed
    {
        if (
            $data instanceof Response ||
            !($operation->canWrite() ?? true) ||
            !$operation->getProcessor()
        ) {
            return $this->processor->process($data, $operation, $context);
        }

        return $this->processor->process($this->locatorProcessor->process($data, $operation, $context), $operation, $context);
    }
}
