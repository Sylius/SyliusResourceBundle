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
use Sylius\Component\Grid\Renderer\BulkActionGridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Twig\Environment;
use Webmozart\Assert\Assert;

final class TwigBulkActionGridRenderer implements BulkActionGridRendererInterface
{
    private Environment $twig;

    private OptionsParserInterface $optionsParser;

    private array $bulkActionTemplates;

    public function __construct(
        Environment $twig,
        OptionsParserInterface $optionsParser,
        array $bulkActionTemplates = [],
    ) {
        $this->twig = $twig;
        $this->optionsParser = $optionsParser;
        $this->bulkActionTemplates = $bulkActionTemplates;
    }

    public function renderBulkAction(GridViewInterface $gridView, Action $bulkAction, $data = null): string
    {
        Assert::isInstanceOf($gridView, ResourceGridView::class);

        $type = $bulkAction->getType();
        if (!isset($this->bulkActionTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for bulk action type "%s".', $type));
        }

        $options = $this->optionsParser->parseOptions(
            $bulkAction->getOptions(),
            $gridView->getRequestConfiguration()->getRequest(),
            $data,
        );

        return $this->twig->render($this->bulkActionTemplates[$type], [
            'grid' => $gridView,
            'action' => $bulkAction,
            'data' => $data,
            'options' => $options,
        ]);
    }
}
