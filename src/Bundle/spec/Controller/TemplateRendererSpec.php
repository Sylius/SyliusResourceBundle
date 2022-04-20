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

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\ResourceBundle\Controller\TemplateRenderer;
use Twig\Environment;

final class TemplateRendererSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(TemplateRenderer::class);
    }

    function it_renders_template_from_twig(Environment $twig): void
    {
        $this->beConstructedWith($twig);

        $twig->render('template.html.twig', [])
            ->willReturn('rendered_string')
            ->shouldBeCalled();

        $this->render('template.html.twig', []);
    }

    function it_throws_a_logic_exception_when_twig_is_not_available(): void
    {
        $this->shouldThrow(\LogicException::class)->during('render', ['template.html.twig', []]);
    }
}
