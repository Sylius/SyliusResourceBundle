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

namespace Sylius\Component\Resource\Metadata;

final class Registry implements RegistryInterface
{
    /** @var array|MetadataInterface[] */
    private array $metadata = [];

    public function getAll(): iterable
    {
        return $this->metadata;
    }

    public function get(string $alias): MetadataInterface
    {
        if (!array_key_exists($alias, $this->metadata)) {
            throw new \InvalidArgumentException(sprintf('Resource "%s" does not exist.', $alias));
        }

        return $this->metadata[$alias];
    }

    public function getByClass(string $className): MetadataInterface
    {
        foreach ($this->metadata as $metadata) {
            if ($className === $metadata->getClass('model')) {
                return $metadata;
            }
        }

        throw new \InvalidArgumentException(sprintf('Resource with model class "%s" does not exist.', $className));
    }

    public function add(MetadataInterface $metadata): void
    {
        $this->metadata[$metadata->getAlias()] = $metadata;
    }

    public function addFromAliasAndConfiguration(string $alias, array $configuration): void
    {
        $this->add(Metadata::fromAliasAndConfiguration($alias, $configuration));
    }
}
