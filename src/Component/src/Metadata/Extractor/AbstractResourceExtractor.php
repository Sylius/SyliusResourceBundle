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
use Sylius\Resource\Exception\RuntimeException;
use Sylius\Resource\Metadata\ResourceMetadata;
use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainerInterface;

/**
 * Base file extractor.
 */
abstract class AbstractResourceExtractor implements ResourceExtractorInterface
{
    /** @var ResourceMetadata[]|null */
    protected ?array $resources = null;

    private array $collectedParameters = [];

    /** @param string[] $paths */
    public function __construct(
        protected array $paths,
        private readonly ?ContainerInterface $container = null,
    ) {
    }

    /**
     * @inheritdoc
     */
    public function getResources(): array
    {
        if (null !== $this->resources) {
            return $this->resources;
        }

        $this->resources = [];
        foreach ($this->paths as $path) {
            $this->extractFromPath($path);
        }

        return $this->resources;
    }

    /**
     * Extracts metadata from a given path.
     */
    abstract protected function extractFromPath(string $path): void;

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
    protected function resolve(mixed $value): mixed
    {
        if (null === $this->container) {
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

        $escapedValue = preg_replace_callback('/%%|%([^%\s]++)%/', function ($match) use ($value) {
            $parameter = $match[1] ?? null;

            // skip %%
            if (!isset($parameter)) {
                return '%%';
            }

            if (preg_match('/^env\(\w+\)$/', $parameter)) {
                throw new RuntimeException(\sprintf('Using "%%%s%%" is not allowed in routing configuration.', $parameter));
            }

            if (\array_key_exists($parameter, $this->collectedParameters)) {
                return $this->collectedParameters[$parameter];
            }

            if ($this->container instanceof SymfonyContainerInterface) {
                $resolved = $this->container->getParameter($parameter);
            } else {
                $resolved = $this->container?->get($parameter);
            }

            if (\is_string($resolved) || is_numeric($resolved)) {
                $this->collectedParameters[$parameter] = $resolved;

                return (string) $resolved;
            }

            throw new RuntimeException(\sprintf('The container parameter "%s", used in the resource configuration value "%s", must be a string or numeric, but it is of type %s.', $parameter, $value, \gettype($resolved)));
        }, $value);

        return str_replace('%%', '%', $escapedValue ?? '');
    }
}
