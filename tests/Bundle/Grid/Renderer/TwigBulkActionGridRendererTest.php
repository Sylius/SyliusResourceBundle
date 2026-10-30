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

namespace Sylius\Bundle\ResourceBundle\Tests\Grid\Renderer;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\Parser\VariablesOptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigBulkActionGridRenderer;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Renderer\BulkActionGridRendererInterface;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\GridContext;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

final class TwigBulkActionGridRendererTest extends TestCase
{
    /** @var Environment&MockObject */
    private Environment $twig;

    /** @var OptionsParserInterface&MockObject */
    private OptionsParserInterface $optionsParser;

    private TwigBulkActionGridRenderer $renderer;

    protected function setUp(): void
    {
        $this->twig = $this->createMock(Environment::class);
        $this->optionsParser = $this->createMock(OptionsParserInterface::class);
        $this->renderer = new TwigBulkActionGridRenderer(
            $this->twig,
            $this->optionsParser,
            ['delete' => '@SyliusGrid/BulkAction/_delete.html.twig'],
        );
    }

    public function testItImplementsBulkActionGridRendererInterface(): void
    {
        $this->assertInstanceOf(BulkActionGridRendererInterface::class, $this->renderer);
    }

    public function testItUsesTwigToRenderTheBulkAction(): void
    {
        $request = $this->createMock(Request::class);
        $requestConfiguration = $this->createConfiguredMock(RequestConfiguration::class, [
            'getRequest' => $request,
        ]);
        $gridView = $this->createConfiguredMock(ResourceGridView::class, [
            'getRequestConfiguration' => $requestConfiguration,
        ]);
        $bulkAction = $this->createBulkActionMock('delete', []);

        $this->configureOptionsParser([], $request);
        $this->configureTwigRenderer(
            '@SyliusGrid/BulkAction/_delete.html.twig',
            $gridView,
            $bulkAction,
            '<a href="#">Delete</a>',
        );

        $result = $this->renderer->renderBulkAction($gridView, $bulkAction);

        $this->assertSame('<a href="#">Delete</a>', $result);
    }

    public function testItThrowsAnExceptionIfTemplateIsNotConfiguredForGivenBulkActionType(): void
    {
        $gridView = $this->createMock(ResourceGridView::class);
        $bulkAction = $this->createBulkActionMock('foo');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing template for bulk action type "foo".');

        $this->renderer->renderBulkAction($gridView, $bulkAction);
    }

    public function testItParsesTheOptionsOfAGridViewWithContext(): void
    {
        $optionsParser = $this->createMockForIntersectionOfInterfaces([OptionsParserInterface::class, VariablesOptionsParserInterface::class]);
        $renderer = new TwigBulkActionGridRenderer($this->twig, $optionsParser, ['delete' => '@SyliusGrid/BulkAction/_delete.html.twig']);

        $gridView = new ContextualGridView([], Grid::fromCodeAndDriverConfiguration('app_book', 'doctrine/orm', []), new Parameters(), new GridContext(variables: ['productId' => 42]));
        $bulkAction = $this->createBulkActionMock('delete', ['productId' => '$productId']);

        $optionsParser->expects($this->once())
            ->method('parseOptionsWithVariables')
            ->with(['productId' => '$productId'], ['productId' => 42], null)
            ->willReturn(['productId' => 42]);

        $this->twig->expects($this->once())
            ->method('render')
            ->with('@SyliusGrid/BulkAction/_delete.html.twig', [
                'grid' => $gridView,
                'action' => $bulkAction,
                'data' => null,
                'options' => ['productId' => 42],
            ])
            ->willReturn('<a href="#">Delete</a>');

        $this->assertSame('<a href="#">Delete</a>', $renderer->renderBulkAction($gridView, $bulkAction));
    }

    public function testItThrowsAnExceptionWithAGridViewWithoutContext(): void
    {
        $gridView = new GridView([], Grid::fromCodeAndDriverConfiguration('app_book', 'doctrine/orm', []), new Parameters());

        $this->expectException(\InvalidArgumentException::class);

        $this->renderer->renderBulkAction($gridView, $this->createBulkActionMock('delete'));
    }

    /**
     * @param array<string, mixed> $options
     */
    private function createBulkActionMock(string $type, array $options = []): Action&MockObject
    {
        return $this->createConfiguredMock(Action::class, [
            'getType' => $type,
            'getOptions' => $options,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    private function configureOptionsParser(array $options, Request $request): void
    {
        $this->optionsParser->expects($this->once())
            ->method('parseOptions')
            ->with($options, $request, null);
    }

    private function configureTwigRenderer(
        string $template,
        ResourceGridView $gridView,
        Action $bulkAction,
        string $renderedContent,
    ): void {
        $this->twig->expects($this->once())
            ->method('render')
            ->with($template, [
                'grid' => $gridView,
                'action' => $bulkAction,
                'data' => null,
                'options' => [],
            ])
            ->willReturn($renderedContent);
    }
}
