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

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
final class Index extends Operation implements CollectionOperationInterface
{
    public function __construct(
        ?string $name = null,
        ?string $path = null,
        ?string $routePrefix = null,
        ?string $controller = null,
        ?string $template = null,
        ?array $repository = null,
        ?array $criteria = null,
        ?array $serializationGroups = null,
        ?string $serializationVersion = null,
        ?array $requirements = null,
        ?array $options = null,
        ?string $host = null,
        ?array $schemes = null,
        ?int $priority = null,
        ?array $vars = null,
        string | array | bool | null $form = null,
        string | array | bool | null $factory = null,
        ?string $section = null,
        ?bool $permission = null,
        ?string $grid = null,
        ?bool $csrfProtection = null,
        string | array | null $redirect = null,
        ?array $stateMachine = null,
        ?string $event = null,
        ?bool $returnContent = null,
        ?string $resource = null,
        ?string $provider = null,
        ?string $processor = null,
        ?bool $read = null,
        ?bool $validate = null,
        ?bool $write = null,
        ?bool $respond = null,
        ?string $input = null,
    ) {
        parent::__construct('index', ['GET'], ...\func_get_args());
    }
}
