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

abstract class Action
{
    public function __construct(
        public ?string $name = null,
        public ?string $path = null,
        public ?array $methods = null,
        public ?string $controller = null,
        public ?string $template = null,
        public ?array $repository = null,
        public ?array $criteria = null,
        public ?array $serializationGroups = null,
        public ?string $serializationVersion = null,
        public ?array $requirements = null,
        public ?array $options = null,
        public ?string $host = null,
        public ?array $schemes = null,
        public ?int $priority = null,
        public ?array $vars = null,
        public string | array | null $form = null,
        public ?string $section = null,
        public ?bool $permission = null,
        public ?string $grid = null,
        public ?bool $csrfProtection = null,
        public string | array | null $redirect = null,
        public ?array $stateMachine = null,
        public ?string $event = null,
        public ?bool $returnContent = null,
        public ?string $resource = null,
        public ?string $provider = null,
        public ?string $processor = null,
        public ?bool $read = null,
        public ?bool $validate = null,
        public ?bool $write = null,
        public ?bool $respond = null,
    ) {
    }
}
