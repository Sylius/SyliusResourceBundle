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

#[\Attribute(\Attribute::TARGET_CLASS)]
final class SyliusCrudRoutes
{
    public ?string $alias = null;
    public ?string $path = null;
    public ?string $identifier = null;
    public ?array $criteria = null;
    public ?bool $filterable = null;
    public ?string $form = null;
    public ?string $serializationVersion = null;
    public ?string $section = null;
    public ?string $redirect = null;
    public ?string $templates = null;
    public ?string $grid = null;
    public ?bool $permission = null;
    public ?array $except = null;
    public ?array $only = null;
    public ?array $vars = null;

    public function __construct(
        ?string $alias = null,
        ?string $path = null,
        ?string $identifier = null,
        ?array $criteria = null,
        ?bool $filterable = null,
        ?string $form = null,
        ?string $serializationVersion = null,
        ?string $section = null,
        ?string $redirect = null,
        ?string $templates = null,
        ?string $grid = null,
        ?bool $permission = null,
        ?array $except = null,
        ?array $only = null,
        ?array $vars = null
    ) {
        $this->alias = $alias;
        $this->path = $path;
        $this->identifier = $identifier;
        $this->criteria = $criteria;
        $this->filterable = $filterable;
        $this->form = $form;
        $this->serializationVersion = $serializationVersion;
        $this->section = $section;
        $this->redirect = $redirect;
        $this->templates = $templates;
        $this->grid = $grid;
        $this->permission = $permission;
        $this->except = $except;
        $this->only = $only;
        $this->vars = $vars;
    }
}
