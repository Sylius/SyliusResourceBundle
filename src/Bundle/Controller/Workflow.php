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

use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Workflow\Registry;
use Webmozart\Assert\Assert;

final class Workflow implements StateMachineInterface
{
    /** @var Registry */
    private $registry;

    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     */
    public function can(RequestConfiguration $configuration, ResourceInterface $resource): bool
    {
        Assert::true($configuration->hasStateMachine(), 'State machine must be configured to apply transition, check your routing.');

        /** @var string $transitionName */
        $transitionName = $configuration->getStateMachineTransition();

        return $this->registry->get($resource)->can($resource, $transitionName);
    }

    /**
     * @inheritdoc
     */
    public function apply(RequestConfiguration $configuration, ResourceInterface $resource): void
    {
        Assert::true($configuration->hasStateMachine(), 'State machine must be configured to apply transition, check your routing.');

        $graph = $configuration->getStateMachineGraph();

        /** @var string $transitionName */
        $transitionName = $configuration->getStateMachineTransition();

        $this->registry->get($resource, $graph)->apply($resource, $transitionName);
    }
}
