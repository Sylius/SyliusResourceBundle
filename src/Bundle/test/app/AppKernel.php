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

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sylius\Bundle\ResourceBundle\SyliusResourceBundle(),
            new BabDev\PagerfantaBundle\BabDevPagerfantaBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new winzou\Bundle\StateMachineBundle\winzouStateMachineBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new AppBundle\AppBundle(),
        ];

        if ($this->getEnvironment() !== 'test_without_fosrest') {
            $bundles[] = new FOS\RestBundle\FOSRestBundle();
            $bundles[] = new JMS\SerializerBundle\JMSSerializerBundle();
            $bundles[] = new Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle();
            $bundles[] = new Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle();
            $bundles[] = new Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/{config}.{php,xml,yaml,yml}', 'glob');
        $loader->load(__DIR__ . '/config/{config}_' . $this->getEnvironment() . '.{php,xml,yaml,yml}', 'glob');
    }
}
