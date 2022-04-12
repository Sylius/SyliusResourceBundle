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

namespace Sylius\Bundle\ResourceBundle\Tests\Maker;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

final class StubMakeResourceTransformerTest extends KernelTestCase
{
    /** @test */
    public function it_informs_maker_bundle_is_not_registered(): void
    {
        $tester = new CommandTester((new Application(self::bootKernel(['environment' => 'test_without_maker'])))->find('make:resource-transformer'));

        $tester->execute([]);

        $this->assertStringContainsString('To run "make:resource-transformer" you need the "MakerBundle"', $tester->getDisplay());
    }
}
