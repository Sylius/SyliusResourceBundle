<?php

namespace Sylius\Resource\Grid\View;

use Sylius\Component\Grid\Definition\Grid;
use Sylius\Component\Grid\Parameters;
use Sylius\Component\Grid\View\GridView;
use Sylius\Resource\Metadata\Operation;
use Sylius\Resource\Metadata\ResourceMetadata;

class ResourceGridView extends GridView
{
    public function __construct(
        $data,
        Grid $gridDefinition,
        Parameters $parameters,
        private ?Operation $operation,
        private ?ResourceMetadata $resourceMetadata,
    ) {
        parent::__construct($data, $gridDefinition, $parameters);
    }

    public function getResourceMetadata(): ?ResourceMetadata
    {
        return $this->resourceMetadata;
    }

    private function getOperation(): ?Operation
    {
        return $this->operation;
    }
}
