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

namespace Sylius\Bundle\ResourceBundle\Grid\Renderer;

use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class TwigGridRenderer implements GridRendererInterface
{
    /** @var GridRendererInterface */
    private $gridRenderer;

    /** @var Environment */
    private $twig;

    /** @var OptionsParserInterface */
    private $optionsParser;

    /** @var array */
    private $actionTemplates;

    public function __construct(
        GridRendererInterface $gridRenderer,
        Environment $twig,
        OptionsParserInterface $optionsParser,
        array $actionTemplates = []
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
        Assert::isInstanceOf($gridView, ResourceGridView::class);

        $type = $action->getType();
        if (!isset($this->actionTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for action type "%s".', $type));
        }

        $options = $this->optionsParser->parseOptions(
            $action->getOptions(),
            $gridView->getRequestConfiguration()->getRequest(),
            $data
        );

        return $this->twig->render($this->actionTemplates[$type], [
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
