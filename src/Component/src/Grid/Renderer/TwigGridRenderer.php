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

namespace Sylius\Resource\Grid\Renderer;

use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\Definition\Filter;
use Sylius\Component\Grid\Renderer\GridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Sylius\Resource\Grid\Parser\OptionsParserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class TwigGridRenderer implements GridRendererInterface
{
    public function __construct(
        private GridRendererInterface $gridRenderer,
        private Environment $twig,
        private OptionsParserInterface $optionsParser,
        private RequestStack $requestStack,
        private array $actionTemplates = [],
    ) {
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
        $type = $action->getType();
        if (!isset($this->actionTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for action type "%s".', $type));
        }

        $options = $this->optionsParser->parseOptions(
            $action->getOptions(),
            $this->requestStack->getCurrentRequest() ?? new Request(),
            $data,
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
