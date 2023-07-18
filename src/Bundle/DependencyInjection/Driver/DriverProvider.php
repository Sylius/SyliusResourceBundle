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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver;

use Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Exception\UnknownDriverException;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Webmozart\Assert\Assert;

final class DriverProvider
{
    /** @var array<string, array<string, string>> */
    private static array $drivers = [];

    public static function build(array $drivers): void
    {
        self::$drivers = $drivers;
    }

    /**
     * @throws UnknownDriverException
     */
    public static function get(MetadataInterface $metadata): DriverInterface
    {
        $type = $metadata->getDriver();

        /** @var class-string|null $class */
        $class = self::$drivers[$type]['class'] ?? null;

        if (null !== $class) {
            /** @var DriverInterface $driver */
            $driver = new $class();

            return $driver;
        }

        Assert::notFalse($type, sprintf('No driver was configured on the resource "%s".', $metadata->getAlias()));

        throw new UnknownDriverException($type);
    }
}
