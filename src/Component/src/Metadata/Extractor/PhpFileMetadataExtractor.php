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

namespace Sylius\Resource\Metadata\Extractor;

use Psr\Container\ContainerInterface;
use Sylius\Resource\Metadata\ResourceMetadata;
use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainerInterface;
use Symfony\Component\Finder\Finder;

final class PhpFileMetadataExtractor implements MetadataExtractorInterface
{
    private array $collectedParameters = [];

    public function __construct(
        private readonly array $resourceMapping,
        private readonly ?ContainerInterface $container = null,
    ) {
    }

    /**
     * @inheritDoc
     */
    public function extract(): array
    {
        $metadata = [];

        foreach ($this->getResourceFilePaths() as $filePath) {
            if (!is_readable($filePath)) {
                continue;
            }

            $resource = $this->getPHPFileClosure($filePath)();

            if (!$resource instanceof ResourceMetadata) {
                continue;
            }

            $resourceReflection = new \ReflectionClass($resource);

            foreach ($resourceReflection->getProperties() as $property) {
                $property->setAccessible(true);
                $resolvedValue = $this->resolve($property->getValue($resource));
                $property->setValue($resource, $resolvedValue);
            }

            $metadata[] = $resource;
        }

        return $metadata;
    }

    private function getResourceFilePaths(): iterable
    {
        foreach ($this->createFinder() as $file) {
            yield $file->getPathname();
        }
    }

    private function createFinder(): Finder
    {
        $finder = (new Finder())->files();

        foreach ($this->resourceMapping['imports'] ?? [] as $path) {
            $finder->in($path);
        }

        return $finder->files();
    }

    /**
     * Scope isolated include.
     *
     * Prevents access to $this/self from included files.
     */
    private function getPHPFileClosure(string $filePath): \Closure
    {
        return \Closure::bind(function () use ($filePath): mixed {
            return require $filePath;
        }, null, null);
    }

    /**
     * Recursively replaces placeholders with the service container parameters.
     *
     * @see https://github.com/symfony/symfony/blob/6fec32c/src/Symfony/Bundle/FrameworkBundle/Routing/Router.php
     *
     * @param mixed $value The source which might contain "%placeholders%"
     *
     * @throws \RuntimeException When a container value is not a string or a numeric value
     *
     * @return mixed The source with the placeholders replaced by the container
     *               parameters. Arrays are resolved recursively.
     */
    private function resolve(mixed $value): mixed
    {
        $container = $this->container;

        if (null === $container) {
            return $value;
        }

        if (\is_array($value)) {
            foreach ($value as $key => $val) {
                $value[$key] = $this->resolve($val);
            }

            return $value;
        }

        if (!\is_string($value)) {
            return $value;
        }

        $escapedValue = preg_replace_callback('/%%|%([^%\s]++)%/', function ($match) use ($value, $container) {
            $parameter = $match[1] ?? null;

            // skip %%
            if (!isset($parameter)) {
                return '%%';
            }

            if (preg_match('/^env\(\w+\)$/', $parameter)) {
                throw new \RuntimeException(\sprintf('Using "%%%s%%" is not allowed in routing configuration.', $parameter));
            }

            if (\array_key_exists($parameter, $this->collectedParameters)) {
                return $this->collectedParameters[$parameter];
            }

            if ($container instanceof SymfonyContainerInterface) {
                $resolved = $container->getParameter($parameter);
            } else {
                $resolved = $container->get($parameter);
            }

            if (\is_string($resolved) || is_numeric($resolved)) {
                $this->collectedParameters[$parameter] = $resolved;

                return (string) $resolved;
            }

            throw new \RuntimeException(\sprintf('The container parameter "%s", used in the resource configuration value "%s", must be a string or numeric, but it is of type %s.', $parameter, $value, \gettype($resolved)));
        }, $value);

        return str_replace('%%', '%', $escapedValue ?? '');
    }
}
