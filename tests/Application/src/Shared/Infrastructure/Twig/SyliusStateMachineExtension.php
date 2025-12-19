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

namespace App\Shared\Infrastructure\Twig;

use SM\Factory\FactoryInterface;
use Symfony\Component\Workflow\Registry;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class SyliusStateMachineExtension extends AbstractExtension
{
    public function __construct(
        private ?Registry $workflowRegistry = null,
        private ?FactoryInterface $factory = null,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_state_machine_can', $this->canTransition(...)),
        ];
    }

    public function canTransition(object $subject, string $transitionName, ?string $name = null): bool
    {
        if (null !== $this->factory) {
            return $this->factory->get($subject, $name ?? 'default')->can($transitionName);
        }

        if (null !== $this->workflowRegistry) {
            return $this->workflowRegistry->get($subject, $name)->can($subject, $transitionName);
        }

        return false;
    }
}
