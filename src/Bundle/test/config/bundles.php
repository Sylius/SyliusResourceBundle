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

use BabDev\PagerfantaBundle\BabDevPagerfantaBundle;
use Bazinga\Bundle\HateoasBundle\BazingaHateoasBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Fidry\AliceDataFixtures\Bridge\Symfony\FidryAliceDataFixturesBundle;
use FOS\RestBundle\FOSRestBundle;
use JMS\SerializerBundle\JMSSerializerBundle;
use Nelmio\Alice\Bridge\Symfony\NelmioAliceBundle;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use winzou\Bundle\StateMachineBundle\winzouStateMachineBundle;

return [
    FrameworkBundle::class => ['all' => true],
    DoctrineBundle::class => ['all' => true],
    SyliusResourceBundle::class => ['all' => true],
    BabDevPagerfantaBundle::class => ['all' => true],
    TwigBundle::class => ['all' => true, 'test_without_twig' => false],
    FOSRestBundle::class => ['test' => true],
    JMSSerializerBundle::class => ['test' => true],
    BazingaHateoasBundle::class => ['test' => true],
    FidryAliceDataFixturesBundle::class => ['test' => true],
    NelmioAliceBundle::class => ['test' => true],
    winzouStateMachineBundle::class => ['all' => true, 'test_without_state_machine' => false],
];
