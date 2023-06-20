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

namespace Sylius\Component\Resource\Symfony\Maker\Generator;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Bundle\MakerBundle\Util\ClassNameDetails;

/**
 * @internal
 */
final class RepositoryClassGenerator
{
    public function __construct(
        private Generator $generator,
    ) {
    }

    public function generateRepositoryClass(ClassNameDetails $repositoryClassDetails, ClassNameDetails $resourceClassDetails): string
    {
        return $this->generator->generateClass(
            $repositoryClassDetails->getFullName(),
            dirname(__DIR__) . '/Resources/skeleton/Repository.tpl.php',
            [
                'entity_class_name' => $resourceClassDetails->getShortName(),
                'entity_namespace' => Str::getNamespace($resourceClassDetails->getFullName()),
            ],
        );
    }
}
