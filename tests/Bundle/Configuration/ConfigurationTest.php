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
            ],
        );
    }

    /**
     * @test
     */
    public function it_has_no_default_mapping_paths()
    {
        $this->assertProcessedConfigurationEquals(
            [
                [],
            ],
            [
                'mapping' => [
                    'paths' => [],
                ],
            ],
            'mapping',
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
            'mapping',
        );
    }

    /**
     * @test
     */
    public function its_default_templates_dir_can_be_customized()
    {
        $this->assertProcessedConfigurationEquals(
            [
                ['settings' => [
                    'default_templates_dir' => 'path/to/templates',
                ]],
            ],
            [
                'settings' => [
                    'default_templates_dir' => 'path/to/templates',
                ],
            ],
            'settings.default_templates_dir',
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
            'authorization_checker',
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
            'authorization_checker',
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
            'authorization_checker',
        );
    }

    protected function getConfiguration()
    {
        return new Configuration();
    }
}
