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

$deprecatedClassesWithAliases = [
    Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration::class => Sylius\Component\Resource\Symfony\HttpFoundation\Request\RequestConfiguration::class,
    Sylius\Bundle\ResourceBundle\Controller\Parameters::class => Sylius\Component\Resource\Symfony\HttpFoundation\Parameters::class,
];

spl_autoload_register(function ($className) use ($deprecatedClassesWithAliases): void {
    if (isset($deprecatedClassesWithAliases[$className])) {
        trigger_deprecation('sylius/resource', '1.11', sprintf('The class %s is deprecated, use %s instead.', $className, $deprecatedClassesWithAliases[$className]));

        class_alias($deprecatedClassesWithAliases[$className], $className);

        return;
    }
});
