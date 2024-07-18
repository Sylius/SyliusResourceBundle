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

use Sylius\Bundle\ResourceBundle\Grid\View\ResourceGridView;
use Sylius\Component\Grid\Definition\Action;
use Sylius\Component\Grid\Renderer\BulkActionGridRendererInterface;
use Sylius\Component\Grid\View\GridViewInterface;
use Sylius\Resource\Grid\Parser\OptionsParserInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Environment;

final class TwigBulkActionGridRenderer implements BulkActionGridRendererInterface
{
    public function __construct(
        private Environment $twig,
        private OptionsParserInterface $optionsParser,
        private array $bulkActionTemplates = [],
        private ?RequestStack $requestStack = null,
    ) {
    }

    public function renderBulkAction(GridViewInterface $gridView, Action $bulkAction, $data = null): string
    {
        $type = $bulkAction->getType();
        if (!isset($this->bulkActionTemplates[$type])) {
            throw new \InvalidArgumentException(sprintf('Missing template for bulk action type "%s".', $type));
        }

        $options = $this->optionsParser->parseOptions(
            $bulkAction->getOptions(),
            $this->getRequest($gridView),
            $data,
        );

        return $this->twig->render($this->bulkActionTemplates[$type], [
            'grid' => $gridView,
            'action' => $bulkAction,
            'data' => $data,
            'options' => $options,
        ]);
    }

    private function getRequest(GridViewInterface $gridView): Request
    {
        if ($gridView instanceof ResourceGridView) {
            return $gridView->getRequestConfiguration()->getRequest();
        }

        return $this->requestStack?->getCurrentRequest() ?? new Request();
    }
}
