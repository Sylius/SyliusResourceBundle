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

use Sylius\Resource\Metadata\Operation\DashPathSegmentNameGenerator;
use Sylius\Resource\Metadata\Operation\UnderscorePathSegmentNameGenerator;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.metadata.path_segment_name_generator.underscore', UnderscorePathSegmentNameGenerator::class)
        ->args([
            service('sylius.metadata.inflector'),
        ])
    ;

    $services->set('sylius.metadata.path_segment_name_generator.dash', DashPathSegmentNameGenerator::class)
        ->args([
            service('sylius.metadata.inflector'),
        ])
    ;
};
