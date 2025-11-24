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
use Sylius\Bundle\ResourceBundle\Grid\Renderer\TwigGridRenderer;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridView;
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
