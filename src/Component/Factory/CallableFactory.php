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

namespace Sylius\Component\Resource\Factory;

use Psr\Container\ContainerInterface;
use Sylius\Bundle\ResourceBundle\Controller\NewResourceFactory;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Webmozart\Assert\Assert;

final class CallableFactory implements FactoryInterface
{
    public function __construct(private ContainerInterface $locator)
    {
    }

    /**
     * @inheritDoc
     */
    public function createNew(/*RequestConfiguration $configuration*/)
    {
        if (0 === func_num_args()) {
            return null;
        }

        /** @var RequestConfiguration $configuration */
        $configuration = func_get_arg(0);
        Assert::isInstanceOf($configuration, RequestConfiguration::class);

        if (!($factory = $configuration->getFactory())) {
            return null;
        }

        if (\is_callable($factory)) {
            return $factory($configuration);
        }

        if (\is_string($factory)) {
            if (!$this->locator->has($factory)) {
                throw new \RuntimeException(sprintf('Factory "%s" not found on operation "%s"', $factory, $configuration->getOperation()));
            }

            /** @var FactoryInterface $factory */
            $factory = $this->locator->get($factory);
            $newResourceFactory = new NewResourceFactory();

            return $newResourceFactory->create($configuration, $factory);
        }

        return null;
    }
}
