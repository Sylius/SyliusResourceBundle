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

namespace Sylius\Bundle\ResourceBundle\Tests\Configuration;

use Matthias\SymfonyConfigTest\PhpUnit\ConfigurationTestCaseTrait;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
    use ConfigurationTestCaseTrait;

    /**
     * @test
     */
    public function it_does_not_break_if_not_customized()
    {
        $this->assertConfigurationIsValid(
            [
                [],
            ]
        );
    }

    /**
     * @test
     */
    public function it_has_default_mapping_paths()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'mapping' => [
                    'paths' => [
                        '%kernel.project_dir%/src/Entity',
                    ],
                ],
            ],
            'mapping'
        );
    }

    /**
     * @test
     */
    public function its_mapping_paths_can_be_customized()
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['mapping' => [
                    'paths' => ['path/to/resources'],
                ]],
            ],
            [
                'mapping' => [
                    'paths' => [
                        'path/to/resources',
                    ],
                ],
            ],
            'mapping'
        );
    }

    /**
     * @test
     */
    public function it_has_default_authorization_checker()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            ['authorization_checker' => 'sylius.resource_controller.authorization_checker.disabled'],
            'authorization_checker'
        );
    }

    /**
     * @test
     */
    public function its_authorization_checker_can_be_customized()
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['authorization_checker' => 'custom_service'],
            ],
            ['authorization_checker' => 'custom_service'],
            'authorization_checker'
        );
    }

    /**
     * @test
     */
    public function its_authorization_checker_cannot_be_empty()
    {
        $this->assertPartialConfigurationIsInvalid(
            [
                ['authorization_checker' => ''],
            ],
            'authorization_checker'
        );
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }
}
