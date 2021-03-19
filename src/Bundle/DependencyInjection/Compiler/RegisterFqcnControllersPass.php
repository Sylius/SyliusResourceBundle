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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\Metadata\Metadata;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;

/** @internal */
final class RegisterFqcnControllersPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        try {
            /** @var array $resources */
            $resources = $container->getParameter('sylius.resources');
        } catch (InvalidArgumentException $exception) {
            return;
        }

        foreach ($resources as $alias => $configuration) {
            $metadata = Metadata::fromAliasAndConfiguration($alias, $configuration);

            if (!$metadata->hasClass('controller')) {
                continue;
            }

            $this->validateSyliusResource($metadata->getClass('model'));
            $controllerFqcn = $metadata->getClass('controller');

            if ($controllerFqcn !== ResourceController::class) {
                $definition = $container->getDefinition($metadata->getServiceId('controller'));

                // TODO: Change to alias definition after bumping to > Symfony 5.1
                $container->setDefinition($metadata->getClass('controller'), $definition);
            }
        }
    }

    private function validateSyliusResource(string $class): void
    {
        if (!in_array(ResourceInterface::class, class_implements($class) ?: [], true)) {
            throw new InvalidArgumentException(sprintf(
                'Class "%s" must implement "%s" to be registered as a Sylius resource.',
                $class,
                ResourceInterface::class
            ));
        }
    }
}
