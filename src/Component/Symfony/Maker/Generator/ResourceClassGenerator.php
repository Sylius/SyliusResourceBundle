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
final class ResourceClassGenerator
{
    public function __construct(
        private Generator $generator,
    ) {
    }

    public function generateResourceClass(
        ClassNameDetails $resourceClassDetails,
        ?ClassNameDetails $repositoryClassDetails,
        bool $isEntity,
    ): string {
        $skeletonName = $isEntity ? 'Entity.tpl.php' : 'Resource.tpl.php';

        $shortName = $resourceClassDetails->getShortName();

        if (\str_ends_with($shortName, 'Resource')) {
            $shortName = \mb_substr($shortName, 0, -\strlen('Resource'));
        }

        return $this->generator->generateClass(
            $resourceClassDetails->getFullName(),
            dirname(__DIR__) . '/Resources/skeleton/' . $skeletonName,
            [
                'class_name_without_suffix' => $shortName,
                'show_template_dir' => \strtolower($shortName),
                'repository_class_name' => $repositoryClassDetails?->getShortName(),
                'repository_namespace' => null !== $repositoryClassDetails ? Str::getNamespace($repositoryClassDetails->getFullName()) : null,
            ],
        );
    }
}
