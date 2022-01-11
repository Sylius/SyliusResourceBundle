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

namespace Sylius\Component\Resource\Annotation;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class SyliusRoute
{
    public ?string $name = null;

    public ?string $path = null;

    public ?array $methods = null;

    public ?string $controller = null;

    public ?string $template = null;

    public ?array $repository = null;

    public ?array $criteria = null;

    public ?string $serializationGroups = null;

    public ?string $serializationVersion = null;

    public ?array $requirements = null;

    public ?array $options = null;

    public ?string $host = null;

    public ?array $schemes = null;

    public ?int $priority = null;

    public ?array $vars = null;

    public function __construct(
        ?string $name = null,
        ?string $path = null,
        ?array $methods = null,
        ?string $controller = null,
        ?string $template = null,
        ?array $repository = null,
        ?array $criteria = null,
        ?string $serializationGroups = null,
        ?string $serializationVersion = null,
        ?array $requirements = null,
        ?array $options = null,
        ?string $host = null,
        ?array $schemes = null,
        ?int $priority = null,
        ?array $vars = null
    ) {
        $this->name = $name;
        $this->path = $path;
        $this->methods = $methods;
        $this->controller = $controller;
        $this->template = $template;
        $this->repository = $repository;
        $this->criteria = $criteria;
        $this->serializationGroups = $serializationGroups;
        $this->serializationVersion = $serializationVersion;
        $this->requirements = $requirements;
        $this->options = $options;
        $this->host = $host;
        $this->schemes = $schemes;
        $this->vars = $vars;
        $this->priority = $priority;
    }
}
