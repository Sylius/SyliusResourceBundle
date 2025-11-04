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

namespace Sylius\Resource\Tests\Symfony\ExpressionLanguage;

use Sylius\Resource\Symfony\ExpressionLanguage\VarsResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

final class VarsResolverTest extends KernelTestCase
{
    public function testMetadataVarsResolver(): void
    {
        $container = static::getContainer();

        /** @var VarsResolverInterface $varsResolver */
        $varsResolver = $container->get('sylius.expression_language.vars_resolver.metadata');

        $this->assertInstanceOf(VarsResolverInterface::class, $varsResolver);
        $this->assertEquals(['has_user' => true], $varsResolver->resolve(['has_user' => '@=token.getUser() === null']));
        $this->assertEquals(['parameters' => ['has_user' => true]], $varsResolver->resolve(['parameters' => ['has_user' => '@=token.getUser() === null']]));
    }
}
