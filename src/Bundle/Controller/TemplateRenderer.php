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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Twig\Environment;

final class TemplateRenderer implements TemplateRendererInterface
{
    private ?Environment $twig;

    public function __construct(?Environment $twig = null)
    {
        $this->twig = $twig;
    }

    public function render(string $template, array $parameters): string
    {
        if (null === $this->twig) {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available. Try running "composer require symfony/twig-bundle".');
        }

        return $this->twig->render($template, $parameters);
    }
}
