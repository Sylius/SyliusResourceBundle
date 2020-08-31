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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection;

use BabDev\PagerfantaBundle\DependencyInjection\BabDevPagerfantaExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sylius\Bundle\ResourceBundle\DependencyInjection\PagerfantaExtension;

final class PagerfantaExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_prepends_the_white_october_bundle_configuration_to_the_babdev_bundle(): void
    {
        $bundleConfig = [
            'default_view' => 'twitter_bootstrap',
            'exceptions_strategy' => [
                'out_of_range_page' => 'custom',
                'not_valid_current_page' => 'to_http_not_found',
            ],
        ];

        // Prepend config now to allow the prepend pass to work
        $this->container->prependExtensionConfig('white_october_pagerfanta', $bundleConfig);

        $this->load($bundleConfig);

        $this->assertSame([$bundleConfig], $this->container->getExtensionConfig('babdev_pagerfanta'));
        $this->assertContainerBuilderHasParameter('white_october_pagerfanta.default_view', $bundleConfig['default_view']);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new PagerfantaExtension(),
            new BabDevPagerfantaExtension(),
        ];
    }
}
