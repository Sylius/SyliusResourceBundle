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

namespace Sylius\Bundle\ResourceBundle\Grid\View;

use Sylius\Bundle\ResourceBundle\Context\Option\RequestConfigurationOption;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\MetadataOption;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Grid\View\GridContext;
use Sylius\Resource\Grid\View\GridContextAwareInterface;
use Sylius\Resource\Metadata\MetadataInterface;

class ResourceGridView extends GridView implements GridContextAwareInterface
{
    private MetadataInterface $metadata;

    private RequestConfiguration $requestConfiguration;

    private ?GridContext $gridContext = null;

    /**
     * @param mixed $data
     */
    public function __construct(
        $data,
        Grid $gridDefinition,
        Parameters $parameters,
        MetadataInterface $resourceMetadata,
        RequestConfiguration $requestConfiguration,
    ) {
        parent::__construct($data, $gridDefinition, $parameters);

        $this->metadata = $resourceMetadata;
        $this->requestConfiguration = $requestConfiguration;
    }

    public function getMetadata(): MetadataInterface
    {
        return $this->metadata;
    }

    public function getRequestConfiguration(): RequestConfiguration
    {
        return $this->requestConfiguration;
    }

    /**
     * The request values (attributes, including the route parameters, then query, then request), as the grid context
     * variables, so that the grid expressions can use "variables['productId']" both in the regular and in the live grids.
     *
     * @return array<string, mixed>
     */
    public function getVariables(): array
    {
        return $this->getGridContext()->getVariables();
    }

    public function getGridContext(): GridContext
    {
        if (null !== $this->gridContext) {
            return $this->gridContext;
        }

        $request = $this->requestConfiguration->getRequest();

        return $this->gridContext = new GridContext(
            metadata: $this->metadata,
            section: $this->requestConfiguration->getSection(),
            // Same precedence as the "$name" placeholders resolved from the request (attributes, query, then request)
            variables: array_merge($request->request->all(), $request->query->all(), $request->attributes->all()),
            context: new Context(
                new MetadataOption($this->metadata),
                new RequestOption($request),
                new RequestConfigurationOption($this->requestConfiguration),
            ),
        );
    }
}
