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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Copied from Symfony to keep using ResourceController as this trait has been removed in Symfony 7.
 * Do not use this trait on your projects, use dependency injection instead.
 *
 * @see https://github.com/symfony/symfony/blob/6.4/src/Symfony/Component/DependencyInjection/ContainerAwareTrait.php
 *
 * @internal
 */
trait ContainerAwareTrait
{
    protected ?ContainerInterface $container = null;

    public function setContainer(?ContainerInterface $container = null): void
    {
        $this->container = $container;
    }
}
