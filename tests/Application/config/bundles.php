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

use BabDev\PagerfantaBundle\BabDevPagerfantaBundle;
use Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use FOS\RestBundle\FOSRestBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use Sylius\Bundle\GridBundle\SyliusGridBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

$bundles = [
    FrameworkBundle::class => ['all' => true],
    SecurityBundle::class => ['all' => true],
    DoctrineBundle::class => ['all' => true],
    SyliusResourceBundle::class => ['all' => true],
    BabDevPagerfantaBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true, 'test_without_twig' => false],
    JMSSerializerBundle::class => ['all' => true, 'test_without_fosrest' => false],
    SyliusGridBundle::class => ['all' => true, 'test_without_twig' => false],
    Zenstruck\Foundry\ZenstruckFoundryBundle::class => ['dev' => true, 'test' => true],
];

if (class_exists(BazingaHateoasBundle::class)) {
    $bundles[BazingaHateoasBundle::class] = ['all' => true, 'test_without_fosrest' => false, 'test_with_attributes' => false];
}

if (class_exists(FOSRestBundle::class)) {
    $bundles[FOSRestBundle::class] = ['all' => true, 'test_without_fosrest' => false];
}

if (class_exists(winzouStateMachineBundle::class)) {
    $bundles[winzouStateMachineBundle::class] = ['all' => true];
}

return $bundles;
