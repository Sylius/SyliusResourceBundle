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

interface FactoryAwareOperationInterface
{
    public function getFactory(): callable|string|false|null;

    public function withFactory(string|callable|false|null $factory): self;

    public function getFactoryMethod(): ?string;

    public function withFactoryMethod(string $factoryMethod): self;

    public function getFactoryArguments(): ?array;

    public function withFactoryArguments(array $factoryArguments): self;
}
