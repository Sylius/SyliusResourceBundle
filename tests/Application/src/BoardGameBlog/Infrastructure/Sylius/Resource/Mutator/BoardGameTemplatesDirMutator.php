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

namespace App\BoardGameBlog\Infrastructure\Sylius\Resource\Mutator;

use App\BoardGameBlog\Infrastructure\Sylius\Resource\BoardGameResource;
use Sylius\Resource\Metadata\AsResourceMutator;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\ResourceMutatorInterface;

#[AsResourceMutator(BoardGameResource::class)]
final class BoardGameTemplatesDirMutator implements ResourceMutatorInterface
{
    public function __invoke(ResourceMetadata $resource): ResourceMetadata
    {
        return $resource->withTemplatesDir('crud');
    }
}
