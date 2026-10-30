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

use Sylius\Bundle\ResourceBundle\Grid\Parser\GridExpressionParser;
use Sylius\Bundle\ResourceBundle\Grid\Parser\OptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\Parser\VariablesOptionsParserInterface;
use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\BulkActionGridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Sylius\Resource\Grid\View\GridContextAwareInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class TwigBulkActionGridRenderer implements BulkActionGridRendererInterface
{
    private Environment $twig;

    private OptionsParserInterface $optionsParser;

    private array $bulkActionTemplates;

    private ?GridExpressionParser $expressionParser;

    public function __construct(
        Environment $twig,
        OptionsParserInterface $optionsParser,
        array $bulkActionTemplates = [],
        ?GridExpressionParser $expressionParser = null,
    ) {
        $this->twig = $twig;
        $this->optionsParser = $optionsParser;
        $this->bulkActionTemplates = $bulkActionTemplates;
        $this->expressionParser = $expressionParser;
    }

    public function renderBulkAction(GridViewInterface $gridView, Action $bulkAction, $data = null): string
    {
        $type = $bulkAction->getType();
        if (!isset($this->bulkActionTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for bulk action type "%s".', $type));
        }

        $options = $this->parseOptions($gridView, $bulkAction->getOptions(), $data);

        return $this->twig->render($this->bulkActionTemplates[$type], [
            'grid' => $gridView,
            'action' => $bulkAction,
            'data' => $data,
            'options' => $options,
        ]);
    }

    /**
     * @param array<array-key, mixed> $options
     *
     * @return array<array-key, mixed>
     */
    private function parseOptions(GridViewInterface $gridView, array $options, mixed $data): array
    {
        // BC layer: the options of the legacy resource grids are still parsed from the request
        if ($gridView instanceof ResourceGridView) {
            $options = $this->optionsParser->parseOptions($options, $gridView->getRequestConfiguration()->getRequest(), $data);
            $variables = $gridView->getVariables();
        } else {
            Assert::isInstanceOf($gridView, GridContextAwareInterface::class);
            Assert::isInstanceOf($this->optionsParser, VariablesOptionsParserInterface::class);

            $variables = $gridView->getGridContext()->getVariables();
            $options = $this->optionsParser->parseOptionsWithVariables($options, $variables, $data);
        }

        return $this->expressionParser?->parseExpressions($options, $variables, ['resource' => $data]) ?? $options;
    }
}
