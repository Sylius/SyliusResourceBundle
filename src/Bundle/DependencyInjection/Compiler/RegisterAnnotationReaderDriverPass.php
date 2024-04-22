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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Doctrine\Common\Annotations\PsrCachedReader;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

/**
 * Workaround to fix Hateoas after annotation_reader service has been removed.
 */
final class RegisterAnnotationReaderDriverPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (
            $container->hasDefinition('annotation_reader')
        ) {
            return;
        }

        $container->setDefinition('annotation_reader', new Definition(
            PsrCachedReader::class,
        ));
    }
}
