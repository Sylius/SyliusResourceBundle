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

namespace Sylius\Resource\Metadata;

/**
 * @method string|null getStateMachineComponent()
 */
interface MetadataInterface
{
    public function getAlias(): string;

    public function getApplicationName(): string;

    public function getName(): string;

    public function getHumanizedName(): string;

    public function getPluralName(): string;

    public function getDriver(): string|false;

    public function getTemplatesNamespace(): ?string;

    /**
     * @return string|array
     *
     * @throws \InvalidArgumentException
     */
    public function getParameter(string $name);

    /**
     * @return class-string $name
     *
     * @throws \InvalidArgumentException
     */
    public function getClass(string $name): string;

    /**
     * Return all the metadata parameters.
     */
    public function getParameters(): array;

    public function hasParameter(string $name): bool;

    public function hasClass(string $name): bool;

    public function getServiceId(string $serviceName): string;

    public function getPermissionCode(string $permissionName): string;
}

class_alias(MetadataInterface::class, \Sylius\Component\Resource\Metadata\MetadataInterface::class);
