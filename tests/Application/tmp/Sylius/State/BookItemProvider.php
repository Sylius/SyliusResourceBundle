<?php

namespace App\Tests\Tmp\Sylius\State;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\State\ProviderInterface;

final class BookItemProvider implements ProviderInterface
{
    public function provide(Operation $operation, Context $context): object|iterable|null
    {
        // Retrieve the state from somewhere

        return null;
    }
}
