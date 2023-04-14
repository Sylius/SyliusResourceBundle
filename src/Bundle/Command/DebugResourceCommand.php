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

namespace Sylius\Bundle\ResourceBundle\Command;

use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Metadata\Operations;
use Sylius\Component\Resource\Metadata\RegistryInterface;
use Sylius\Component\Resource\Metadata\Resource as ResourceMetadata;
use Sylius\Component\Resource\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Dumper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class DebugResourceCommand extends Command
{
    public function __construct(
        private RegistryInterface $registry,
        private ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
    ) {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->setName('sylius:debug:resource');
        $this->setDescription('Debug resource metadata.');
        $this->setHelp(
            <<<'EOT'
List or show resource metadata.

To list run the command without an argument:

    $ php %command.full_name%

To show the metadata for a resource, pass its alias:

    $ php %command.full_name% sylius.user
EOT
        );
        $this->addArgument('resource', InputArgument::OPTIONAL, 'Resource to debug');
    }

    public function execute(InputInterface $input, OutputInterface $output): ?int
    {
        /** @var string|null $resource */
        $resource = $input->getArgument('resource');

        $io = new SymfonyStyle($input, $output);

        $dumper = new Dumper($output);

        if (null === $resource) {
            $this->listResources($io);

            return 0;
        }

        if (str_contains($resource, '.')) {
            $metadata = $this->registry->get($resource);
        } else {
            $metadata = $this->registry->getByClass($resource);
        }

        $this->debugResource($metadata, $io, $dumper);

        return 0;
    }

    private function listResources(SymfonyStyle $io): void
    {
        /** @var iterable<MetadataInterface> $resources */
        $resources = $this->registry->getAll();
        $resources = is_array($resources) ? $resources : iterator_to_array($resources);
        ksort($resources);

        $rows = [];

        foreach ($resources as $resource) {
            $rows[] = [$resource->getAlias()];
        }

        $io->table(['Alias'], $rows);
    }

    private function debugResource(MetadataInterface $metadata, SymfonyStyle $io, Dumper $dumper): void
    {
        $io->section('Configuration');

        $values = $this->configurationToArray($metadata);

        $rows = [];

        foreach ($values as $key => $value) {
            $rows[] = [$key, $dumper($value)];
        }

        $io->table(['Option', 'Value'], $rows);

        $this->debugNewResourceMetadata($metadata, $io, $dumper);

        $this->debugResourceCollectionOperation($metadata, $io, $dumper);
    }

    private function debugNewResourceMetadata(MetadataInterface $metadata, SymfonyStyle $io, Dumper $dumper): void
    {
        $io->section('New Resource Metadata');

        $resourceMetadataCollection = $this->resourceMetadataCollectionFactory->create($metadata->getClass('model'));

        if (0 === $resourceMetadataCollection->count()) {
            $io->info('This resource has no new metadata.');

            return;
        }

        /** @var ResourceMetadata $resourceMetadata */
        foreach ($resourceMetadataCollection as $resourceMetadata) {
            $rows = [];

            $values = $this->resourceToArray($resourceMetadata);
            foreach ($values as $key => $value) {
                $rows[] = [$key, $dumper($value)];
            }

            $io->table(['Option', 'Value'], $rows);
        }
    }

    private function debugResourceCollectionOperation(MetadataInterface $metadata, SymfonyStyle $io, Dumper $dumper): void
    {
        $io->section('New operations');

        $resourceMetadataCollection = $this->resourceMetadataCollectionFactory->create($metadata->getClass('model'));

        $rows = [];

        /** @var ResourceMetadata $resourceMetadata */
        foreach ($resourceMetadataCollection as $resourceMetadata) {
            $rows = $this->addResourceOperationsRows($resourceMetadata, $rows, $dumper);
        }

        if ($rows === []) {
            $io->info('This resource has no defined operations.');

            return;
        }

        $io->table(['Name'], $rows);
    }

    private function addResourceOperationsRows(ResourceMetadata $resourceMetadata, array $rows, Dumper $dumper): array
    {
        /** @var Operation $operation */
        foreach ($resourceMetadata->getOperations() ?? new Operations() as $operation) {
            $rows[] = [
                $operation->getName(),
            ];
        }

        return $rows;
    }

    private function configurationToArray(MetadataInterface $metadata): array
    {
        $values = $this->objectToArray($metadata);

        $values = array_merge($values, $values['parameters']);
        unset($values['parameters']);

        return $values;
    }

    private function resourceToArray(ResourceMetadata $resource): array
    {
        $values = $this->objectToArray($resource);

        unset($values['operations']);

        return $values;
    }

    private function objectToArray(object $object): array
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $reflection = new \ReflectionClass($object);

        $values = [];

        foreach ($reflection->getProperties() as $property) {
            $propertyName = $property->getName();

            if ($accessor->isReadable($object, $propertyName)) {
                $values[$property->getName()] = $accessor->getValue($object, $propertyName);
            }
        }

        return $values;
    }
}
