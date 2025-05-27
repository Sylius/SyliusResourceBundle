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

namespace Sylius\Bundle\ResourceBundle\Grid\Renderer;

use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Twig\Environment;

final class TwigGridRenderer implements GridRendererInterface
{
    private GridRendererInterface $gridRenderer;

    private Environment $twig;

    private OptionsParserInterface $optionsParser;

    private array $actionTemplates;

    public function __construct(
        GridRendererInterface $gridRenderer,
        Environment $twig,
        OptionsParserInterface $optionsParser,
        array $actionTemplates = [],
    ) {
        $this->gridRenderer = $gridRenderer;
        $this->twig = $twig;
        $this->optionsParser = $optionsParser;
        $this->actionTemplates = $actionTemplates;
    }

    public function render(GridViewInterface $gridView, ?string $template = null): string
    {
        return $this->gridRenderer->render($gridView, $template);
    }

    /**
     * @param mixed $data
     */
    public function renderField(GridViewInterface $gridView, Field $field, $data): string
    {
        return $this->gridRenderer->renderField($gridView, $field, $data);
    }

    /**
     * @param mixed $data
     */
    public function renderAction(GridViewInterface $gridView, Action $action, $data = null): string
    {
        if (!$gridView instanceof ResourceGridView) {
            return $this->gridRenderer->renderAction($gridView, $action, $data);
        }

        $type = $action->getType();
        $template = method_exists($action, 'getTemplate') ? $action->getTemplate() : null;
        $template ??= $this->actionTemplates[$type] ?? null;

        if (null === $template) {
            throw new \InvalidArgumentException(sprintf('Missing template for action type "%s".', $type));
        }

        $options = $this->optionsParser->parseOptions(
            $action->getOptions(),
            $gridView->getRequestConfiguration()->getRequest(),
            $data,
        );

        return $this->twig->render($template, [
            'grid' => $gridView,
            'action' => $action,
            'data' => $data,
            'options' => $options,
        ]);
    }

    public function renderFilter(GridViewInterface $gridView, Filter $filter): string
    {
        return $this->gridRenderer->renderFilter($gridView, $filter);
    }
}
