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

use FOS\RestBundle\View\ConfigurableViewHandlerInterface;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Response;

final class ViewHandler implements ViewHandlerInterface
{
    /** @var ConfigurableViewHandlerInterface */
    private $restViewHandler;

    public function __construct(ConfigurableViewHandlerInterface $restViewHandler)
    {
        $this->restViewHandler = $restViewHandler;
    }

    public function handle(RequestConfiguration $requestConfiguration, View $view): Response
    {
        if (!$requestConfiguration->isHtmlRequest()) {
            $this->restViewHandler->setExclusionStrategyGroups($requestConfiguration->getSerializationGroups() ?? []);

            if ($version = $requestConfiguration->getSerializationVersion()) {
                $this->restViewHandler->setExclusionStrategyVersion($version);
            }

            $view->getContext()->enableMaxDepth();
        }

        return $this->restViewHandler->handle($view);
    }
}
