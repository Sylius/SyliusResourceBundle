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

namespace Sylius\Resource\Grid\View;

use Sylius\Resource\Context\Context;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\Operation;

/**
 * What the grid renderers and the action templates need to know about where a grid is displayed,
 * without depending on the current request.
 *
 * It can be built by the legacy resource controller (from the request configuration),
 * by the metadata-based routing (from the operation), or by hand (e.g. from the props of a live component).
 *
 * @experimental
 */
final class GridContext
{
    /**
     * @param array<string, mixed> $variables Values of the "$name" placeholders of the grid options (e.g. the route parameters)
     */
    public function __construct(
        private ?MetadataInterface $metadata = null,
        private ?string $section = null,
        private array $variables = [],
        private ?Operation $operation = null,
        private Context $context = new Context(),
    ) {
    }

    public function getMetadata(): ?MetadataInterface
    {
        return $this->metadata;
    }

    public function getSection(): ?string
    {
        return $this->section ?? $this->operation?->getResource()?->getSection();
    }

    /** @return array<string, mixed> */
    public function getVariables(): array
    {
        return $this->variables;
    }

    public function getVariable(string $name, mixed $default = null): mixed
    {
        return array_key_exists($name, $this->variables) ? $this->variables[$name] : $default;
    }

    public function getOperation(): ?Operation
    {
        return $this->operation;
    }

    /** Extra options, e.g. the legacy request configuration (see RequestConfigurationOption). */
    public function getContext(): Context
    {
        return $this->context;
    }

    /** @param array<string, mixed> $variables */
    public function withVariables(array $variables): self
    {
        $self = clone $this;
        $self->variables = array_merge($this->variables, $variables);

        return $self;
    }

    /**
     * Same route names as RequestConfiguration::getRouteName() and OperationRouteNameFactory, e.g. "sylius_admin_product_update".
     */
    public function getRouteName(string $name): string
    {
        $resource = $this->operation?->getResource();

        $applicationName = $this->metadata?->getApplicationName() ?? $resource?->getApplicationName();
        $resourceName = $this->metadata?->getName() ?? $resource?->getName();

        if (null === $applicationName || null === $resourceName) {
            throw new \LogicException('The route name cannot be guessed without resource metadata or operation.');
        }

        $section = $this->getSection();
        $sectionPrefix = $section ? $section . '_' : '';

        return sprintf('%s_%s%s_%s', $applicationName, $sectionPrefix, $resourceName, $name);
    }
}
