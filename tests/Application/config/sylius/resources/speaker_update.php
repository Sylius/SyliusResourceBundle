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

use App\Conference\Entity\Speaker;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Update;

return (new ResourceMetadata())
    ->withRoutePrefix('/admin')
    ->withClass(Speaker::class)
    ->withSection('admin')
    ->withTemplatesDir('crud')
    ->withOperations(new Operations([
        new Update(),
    ]))
;
