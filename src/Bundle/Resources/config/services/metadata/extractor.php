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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Resource\Metadata\Extractor\PhpFileResourceExtractor;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.metadata.resource_extractor.php_file', PhpFileResourceExtractor::class)
        ->args([
            [],
            service('service_container'),
        ]);
};
