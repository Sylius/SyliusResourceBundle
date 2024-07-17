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

namespace spec\Sylius\Bundle\ResourceBundle\Grid\Renderer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class TwigGridRendererSpec extends ObjectBehavior
{
    function let(
        GridRendererInterface $gridRenderer,
        Environment $twig,
        OptionsParserInterface $optionsParser,
    ): void {
        $actionTemplates = [
            'link' => '@SyliusGrid/Action/_link.html.twig',
            'form' => '@SyliusGrid/Action/_form.html.twig',
        ];

        $this->beConstructedWith(
            $gridRenderer,
            $twig,
            $optionsParser,
            new RequestStack(),
            $actionTemplates,
        );
    }

    function it_is_a_grid_renderer(): void
    {
        $this->shouldImplement(GridRendererInterface::class);
    }

    function it_uses_twig_to_render_the_action(
        Environment $twig,
        OptionsParserInterface $optionsParser,
        GridViewInterface $gridView,
        Action $action,
    ): void {
        $action->getType()->willReturn('link');
        $action->getOptions()->willReturn([]);

        $optionsParser->parseOptions([], Argument::type(Request::class), null)->shouldBeCalled();

        $twig
            ->render('@SyliusGrid/Action/_link.html.twig', [
                'grid' => $gridView,
                'action' => $action,
                'data' => null,
                'options' => [],
            ])
            ->willReturn('<a href="#">Action!</a>')
        ;

        $this->renderAction($gridView, $action)->shouldReturn('<a href="#">Action!</a>');
    }

    function it_throws_an_exception_if_template_is_not_configured_for_given_action_type(
        ResourceGridView $gridView,
        Action $action,
    ): void {
        $action->getType()->willReturn('foo');

        $this
            ->shouldThrow(new \InvalidArgumentException('Missing template for action type "foo".'))
            ->during('renderAction', [$gridView, $action])
        ;
    }
}
