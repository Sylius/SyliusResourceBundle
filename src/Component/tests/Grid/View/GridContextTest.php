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

namespace Sylius\Resource\Tests\Grid\View;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\View\GridContext;
use Sylius\Resource\Metadata\Index;
use Sylius\Resource\Metadata\Metadata;
use Sylius\Resource\Metadata\ResourceMetadata;
use Symfony\Component\HttpFoundation\Request;

final class GridContextTest extends TestCase
{
    public function testItIsEmptyByDefault(): void
    {
        $gridContext = new GridContext();

        $this->assertNull($gridContext->getMetadata());
        $this->assertNull($gridContext->getSection());
        $this->assertSame([], $gridContext->getVariables());
        $this->assertNull($gridContext->getOperation());
        $this->assertSame([], iterator_to_array($gridContext->getContext()));
    }

    public function testItExposesItsVariables(): void
    {
        $gridContext = new GridContext(variables: ['productId' => 42, 'nullable' => null]);

        $this->assertSame(42, $gridContext->getVariable('productId'));
        $this->assertNull($gridContext->getVariable('nullable', 'default'));
        $this->assertSame('default', $gridContext->getVariable('missing', 'default'));
    }

    public function testItAddsVariablesWithoutModifyingTheOriginalContext(): void
    {
        $gridContext = new GridContext(variables: ['productId' => 42, 'page' => 1]);
        $newGridContext = $gridContext->withVariables(['page' => 2]);

        $this->assertSame(['productId' => 42, 'page' => 1], $gridContext->getVariables());
        $this->assertSame(['productId' => 42, 'page' => 2], $newGridContext->getVariables());
    }

    public function testItKeepsExtraContextOptions(): void
    {
        $requestOption = new RequestOption(new Request());

        $gridContext = new GridContext(context: new Context($requestOption));

        $this->assertSame($requestOption, $gridContext->getContext()->get(RequestOption::class));
    }

    public function testItGuessesRouteNamesFromMetadata(): void
    {
        $gridContext = new GridContext(metadata: Metadata::fromAliasAndConfiguration('sylius.product', ['driver' => 'doctrine/orm']), section: 'admin');

        $this->assertSame('sylius_admin_product_update', $gridContext->getRouteName('update'));
    }

    public function testItGuessesRouteNamesWithoutSection(): void
    {
        $gridContext = new GridContext(metadata: Metadata::fromAliasAndConfiguration('sylius.product', ['driver' => 'doctrine/orm']));

        $this->assertSame('sylius_product_show', $gridContext->getRouteName('show'));
    }

    public function testItGuessesRouteNamesAndSectionFromOperation(): void
    {
        $operation = (new Index())->withResource(new ResourceMetadata(alias: 'app.book', section: 'admin', name: 'book', applicationName: 'app'));

        $gridContext = new GridContext(operation: $operation);

        $this->assertSame($operation, $gridContext->getOperation());
        $this->assertSame('admin', $gridContext->getSection());
        $this->assertSame('app_admin_book_delete', $gridContext->getRouteName('delete'));
    }

    public function testItPrefersTheGivenSection(): void
    {
        $operation = (new Index())->withResource(new ResourceMetadata(alias: 'app.book', section: 'admin', name: 'book', applicationName: 'app'));

        $gridContext = new GridContext(section: 'shop', operation: $operation);

        $this->assertSame('app_shop_book_delete', $gridContext->getRouteName('delete'));
    }

    public function testItCannotGuessRouteNamesWithoutMetadataNorOperation(): void
    {
        $this->expectException(\LogicException::class);

        (new GridContext(section: 'admin'))->getRouteName('update');
    }
}
