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

use Doctrine\Inflector\Inflector as InflectorObject;
use Doctrine\Inflector\InflectorFactory;

final class Metadata implements MetadataInterface
{
    private string $name;

    private string $applicationName;

    /** @var string */
    private $driver;

    /** @var string */
    private $templatesNamespace;

    /** @var array */
    private $parameters;

    private static ?InflectorObject $inflectorInstance = null;

    private function __construct(string $name, string $applicationName, array $parameters)
    {
        $this->name = $name;
        $this->applicationName = $applicationName;

        $this->driver = $parameters['driver'];
        $this->templatesNamespace = array_key_exists('templates', $parameters) ? $parameters['templates'] : null;

        $this->parameters = $parameters;
    }

    public static function fromAliasAndConfiguration(string $alias, array $parameters): self
    {
        [$applicationName, $name] = self::parseAlias($alias);

        return new self($name, $applicationName, $parameters);
    }

    public static function setInflector(InflectorObject $inflector): void
    {
        self::$inflectorInstance = $inflector;
    }

    private static function getInflector(): InflectorObject
    {
        if (self::$inflectorInstance === null) {
            $inflectorFactory = InflectorFactory::create();

            self::$inflectorInstance = $inflectorFactory->build();
        }

        return self::$inflectorInstance;
    }

    public function getAlias(): string
    {
        return $this->applicationName . '.' . $this->name;
    }

    public function getApplicationName(): string
    {
        return $this->applicationName;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHumanizedName(): string
    {
        return strtolower(trim((string) preg_replace(['/([A-Z])/', '/[_\s]+/'], ['_$1', ' '], $this->name)));
    }

    public function getPluralName(): string
    {
        return self::getInflector()->pluralize($this->name);
    }

    public function getDriver(): string
    {
        return $this->driver;
    }

    public function getTemplatesNamespace(): ?string
    {
        return $this->templatesNamespace;
    }

    public function getParameter(string $name)
    {
        if (!$this->hasParameter($name)) {
            throw new \InvalidArgumentException(sprintf('Parameter "%s" is not configured for resource "%s".', $name, $this->getAlias()));
        }

        return $this->parameters[$name];
    }

    public function hasParameter(string $name): bool
    {
        return array_key_exists($name, $this->parameters);
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function getClass(string $name): string
    {
        if (!$this->hasClass($name)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" is not configured for resource "%s".', $name, $this->getAlias()));
        }

        return $this->parameters['classes'][$name];
    }

    public function hasClass(string $name): bool
    {
        return isset($this->parameters['classes'][$name]);
    }

    public function getServiceId(string $serviceName): string
    {
        return sprintf('%s.%s.%s', $this->applicationName, $serviceName, $this->name);
    }

    public function getPermissionCode(string $permissionName): string
    {
        return sprintf('%s.%s.%s', $this->applicationName, $this->name, $permissionName);
    }

    private static function parseAlias(string $alias): array
    {
        if (false === strpos($alias, '.')) {
            throw new \InvalidArgumentException(sprintf('Invalid alias "%s" supplied, it should conform to the following format "<applicationName>.<name>".', $alias));
        }

        return explode('.', $alias);
    }
}
