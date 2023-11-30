<?php

declare(strict_types=1);

use Arkitect\ClassSet;
use Arkitect\CLI\Config;
use Arkitect\Expression\ForClasses\IsFinal;
use Arkitect\Expression\ForClasses\NotDependsOnTheseNamespaces;
use Arkitect\Expression\ForClasses\ResideInOneOfTheseNamespaces;
use Arkitect\Rules\Rule;

return static function (Config $config): void
{
    $srcClassSet = ClassSet::fromDir(__DIR__ . '/src');

    $rules = [];

    $rules[] = Rule::allClasses()
        ->except(
            // Except class aliases
            'Sylius\Resource\Symfony\EventDispatcher\*',
        )
        ->that(new ResideInOneOfTheseNamespaces('Sylius\Resource'))
        ->should(new NotDependsOnTheseNamespaces('Sylius\Bundle'))
        ->because('Sylius Resource should be stand-alone')
    ;

    $rules[] = Rule::allClasses()
        ->except(
            'Sylius\Resource\Metadata\HttpOperation',
            'Sylius\Resource\Symfony\EventDispatcher\GenericEvent',
        )
        ->that(new ResideInOneOfTheseNamespaces('Sylius\Resource'))
        ->should(new IsFinal())
        ->because('We want to avoid forgetting final classes');

    $config->add($srcClassSet, ...$rules);
};
