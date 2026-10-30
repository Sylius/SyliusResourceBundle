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

namespace Sylius\Bundle\ResourceBundle\Tests\Grid\ExpressionLanguage;

use Sylius\Component\Grid\View\VariablesResolverInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class GridExpressionsIntegrationTest extends KernelTestCase
{
    public function testTheGridExpressionsHaveTheResourceVariables(): void
    {
        /** @var RequestStack $requestStack */
        $requestStack = self::getContainer()->get('request_stack');
        $requestStack->push(new Request(attributes: ['slug' => 'from-request']));

        $this->assertSame(
            ['from-request', true, 'from-variables'],
            $this->getVariablesResolver()->resolve(
                ["@=request.attributes.get('slug')", "@=sylius_repositories.has('app.repository.book')", "@=variables['slug']"],
                ['slug' => 'from-variables'],
            ),
        );
    }

    public function testTheGridExpressionsHaveTheResourceFunctions(): void
    {
        $this->expectException(NotFoundHttpException::class);

        $this->getVariablesResolver()->resolveValue("@=throw_not_found_on_null(variables['missing'] ?? null)", []);
    }

    private function getVariablesResolver(): VariablesResolverInterface
    {
        /** @var VariablesResolverInterface $variablesResolver */
        $variablesResolver = self::getContainer()->get(VariablesResolverInterface::class);

        return $variablesResolver;
    }
}
