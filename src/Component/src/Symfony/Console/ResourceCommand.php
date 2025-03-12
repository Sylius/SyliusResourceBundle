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

namespace Sylius\Resource\Symfony\Console;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Sylius\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Sylius\Resource\Metadata\Resource\ResourceMetadataCollection;
use Sylius\Resource\State\ProcessorInterface;
use Sylius\Resource\State\ProviderInterface;
use Sylius\Resource\Symfony\Console\Context\ConsoleOption;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ResourceCommand extends Command
{
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private readonly RegistryInterface $registry,
        private readonly ProviderInterface $provider,
        private readonly ProcessorInterface $processor,
        private ?string $resource = null,
        ?string $name = null,
    ) {
        parent::__construct($name ?? 'sylius:operation:resource');
    }

    protected function configure(): void
    {
        $this
            ->addOption('id', null, InputOption::VALUE_REQUIRED, 'The resource id.')
            ->addOption('resource', null, InputOption::VALUE_REQUIRED, 'Resource alias or Resource FQCN.')
            ->addOption('operation', null, InputOption::VALUE_REQUIRED, 'Operation name.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $resource = $this->resource ?? $input->getOption('resource');

        if (str_contains($resource, '.')) {
            $metadata = $this->registry->get($resource);
        } else {
            $metadata = $this->registry->getByClass($resource);
        }

        $resourceMetadataCollection = $this->getResourceMetadataCollection($metadata);

        $operation = $resourceMetadataCollection->getOperation($metadata->getAlias(), $this->createOperationName($input));

        $context = new Context(new ConsoleOption($this, $input, $output));

        $data = $this->provider->provide($operation, $context);

        return $this->processor->process($data, $operation, $context);
    }

    private function getResourceMetadataCollection(MetadataInterface $resourceConfiguration): ResourceMetadataCollection
    {
        return $this->resourceMetadataCollectionFactory->create($resourceConfiguration->getClass('model'));
    }

    private function createOperationName(InputInterface $input): string
    {
        $operationName = $this->getName() ?? $input->getOption('operation');

        if (null !== $operationName) {
            return $operationName;
        }

        return '';
    }
}
