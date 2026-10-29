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
use Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigGridRenderer;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Grid\View\ContextualGridView;
use Sylius\Resource\Grid\View\GridContext;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;

final class TwigGridRendererTest extends TestCase
{
    private const ACTION_TEMPLATES = [
        'link' => '@SyliusGrid/Action/_link.html.twig',
        'form' => '@SyliusGrid/Action/_form.html.twig',
    ];

    /** @var GridRendererInterface&MockObject */
    private GridRendererInterface $gridRenderer;

    /** @var Environment&MockObject */
    private Environment $twig;

    /** @var OptionsParserInterface&MockObject */
    private OptionsParserInterface $optionsParser;

    private TwigGridRenderer $renderer;

    protected function setUp(): void
    {
        $this->gridRenderer = $this->createMock(GridRendererInterface::class);
        $this->twig = $this->createMock(Environment::class);
        $this->optionsParser = $this->createMock(OptionsParserInterface::class);
        $this->renderer = new TwigGridRenderer(
            $this->gridRenderer,
            $this->twig,
            $this->optionsParser,
            self::ACTION_TEMPLATES,
        );
    }

    public function testItImplementsGridRendererInterface(): void
    {
        $this->assertInstanceOf(GridRendererInterface::class, $this->renderer);
    }

    public function testItUsesTwigToRenderTheAction(): void
    {
        $request = $this->createMock(Request::class);
        $requestConfiguration = $this->createConfiguredMock(RequestConfiguration::class, [
            'getRequest' => $request,
        ]);
        $gridView = $this->createConfiguredMock(ResourceGridView::class, [
            'getRequestConfiguration' => $requestConfiguration,
        ]);
        $action = $this->createActionMock('link', [], null);

        $this->configureOptionsParser([], $request);
        $this->configureTwigRenderer(
            '@SyliusGrid/Action/_link.html.twig',
            $gridView,
            $action,
            '<a href="#">Action!</a>',
        );

        $result = $this->renderer->renderAction($gridView, $action);

        $this->assertSame('<a href="#">Action!</a>', $result);
    }

    public function testItThrowsAnExceptionIfTemplateIsNotConfiguredForGivenActionType(): void
    {
        $gridView = $this->createMock(ResourceGridView::class);
        $action = $this->createActionMock('foo', [], null);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing template for action type "foo".');

        $this->renderer->renderAction($gridView, $action);
    }

    public function testItCallsTheInnerRendererWithANonResourceGridView(): void
    {
        $gridView = $this->createMock(GridView::class);
        $action = $this->createMock(Action::class);

        $this->configureInnerRenderer($gridView, $action, 'foo');

        $result = $this->renderer->renderAction($gridView, $action);

        $this->assertSame('foo', $result);
    }

    public function testItParsesTheOptionsOfAGridViewWithContext(): void
    {
        $optionsParser = $this->createMockForIntersectionOfInterfaces([OptionsParserInterface::class, VariablesOptionsParserInterface::class]);
        $renderer = new TwigGridRenderer($this->gridRenderer, $this->twig, $optionsParser, self::ACTION_TEMPLATES);

        $gridView = new ContextualGridView([], Grid::fromCodeAndDriverConfiguration('app_book', 'doctrine/orm', []), new Parameters(), new GridContext(variables: ['productId' => 42]));
        $action = $this->createActionMock('link', ['link' => ['parameters' => ['productId' => '$productId']]]);

        $optionsParser->expects($this->never())->method('parseOptions');
        $optionsParser->expects($this->once())
            ->method('parseOptionsWithVariables')
            ->with(['link' => ['parameters' => ['productId' => '$productId']]], ['productId' => 42], null)
            ->willReturn(['link' => ['parameters' => ['productId' => 42]]]);

        $this->twig->expects($this->once())
            ->method('render')
            ->with('@SyliusGrid/Action/_link.html.twig', [
                'grid' => $gridView,
                'action' => $action,
                'data' => null,
                'options' => ['link' => ['parameters' => ['productId' => 42]]],
            ])
            ->willReturn('<a href="#">Action!</a>');

        $this->assertSame('<a href="#">Action!</a>', $renderer->renderAction($gridView, $action));
    }

    public function testItCallsTheInnerRendererWhenTheOptionsParserDoesNotSupportVariables(): void
    {
        $gridView = new ContextualGridView([], Grid::fromCodeAndDriverConfiguration('app_book', 'doctrine/orm', []), new Parameters(), new GridContext());
        $action = $this->createMock(Action::class);

        $this->configureInnerRenderer($gridView, $action, 'foo');

        $this->assertSame('foo', $this->renderer->renderAction($gridView, $action));
    }

    /**
     * @param array<string, mixed> $options
     */
    private function createActionMock(string $type, array $options = [], ?string $template = null): Action&MockObject
    {
        return $this->createConfiguredMock(Action::class, [
            'getType' => $type,
            'getOptions' => $options,
            'getTemplate' => $template,
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
        Action $action,
        string $renderedContent,
    ): void {
        $this->twig->expects($this->once())
            ->method('render')
            ->with($template, [
                'grid' => $gridView,
                'action' => $action,
                'data' => null,
                'options' => [],
            ])
            ->willReturn($renderedContent);
    }

    private function configureInnerRenderer(GridView $gridView, Action $action, string $result): void
    {
        $this->gridRenderer->expects($this->once())
            ->method('renderAction')
            ->with($gridView, $action, null)
            ->willReturn($result);
    }
}
