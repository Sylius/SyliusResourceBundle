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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\DisabledAuthorizationChecker;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Controller\AuthorizationCheckerInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

final class DisabledAuthorizationCheckerTest extends TestCase
{
    private DisabledAuthorizationChecker $disabledAuthorizationChecker;
    protected function setUp(): void
    {
        $this->disabledAuthorizationChecker = new DisabledAuthorizationChecker();
    }
    function testImplementsResourceControllerAuthorizationCheckerInterface(): void
    {
        $this->assertInstanceOf(AuthorizationCheckerInterface::class, $this->disabledAuthorizationChecker);
    }

    function testAlwaysReturnsTrue(): void
    {
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        $this->assertTrue($this->disabledAuthorizationChecker->isGranted($requestConfigurationMock, 'create'));
        $this->assertTrue($this->disabledAuthorizationChecker->isGranted($requestConfigurationMock, 'update'));
        $this->assertTrue($this->disabledAuthorizationChecker->isGranted($requestConfigurationMock, 'custom'));
    }
}
