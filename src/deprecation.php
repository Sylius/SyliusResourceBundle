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

$deprecatedInterfaces = [
    Sylius\Bundle\ResourceBundle\Controller\ParametersParserInterface::class => Sylius\Component\Resource\Symfony\Request\ParametersParserInterface::class,
];

foreach ($deprecatedInterfaces as $oldInterfaceName => $interfaceName) {
    // Do not replace existing interface
    if (interface_exists($oldInterfaceName)) {
        continue;
    }

    if (!interface_exists($interfaceName)) {
        dump("interface $interfaceName does not exist, replacement $oldInterfaceName neither");

        continue;
    }

    class_alias($interfaceName, $oldInterfaceName);
}

$deprecatedClassesWithAliases = [
    Sylius\Bundle\ResourceBundle\Controller\ParametersParser::class => Sylius\Component\Resource\Symfony\Request\ParametersParser::class,

    Sylius\Bundle\ResourceBundle\Provider\RequestParameterProvider::class => Sylius\Component\Resource\Symfony\Request\RequestParameterProvider::class,
];

spl_autoload_register(function ($className) use ($deprecatedClassesWithAliases): void {
    if (isset($deprecatedClassesWithAliases[$className])) {
        trigger_deprecation('sylius/resource', '1.11', sprintf('The class %s is deprecated, use %s instead.', $className, $deprecatedClassesWithAliases[$className]));

        class_alias($deprecatedClassesWithAliases[$className], $className);

        return;
    }
});
