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

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser;
use Sylius\Resource\Symfony\ExpressionLanguage\Provider\ThrowNotFoundOnNullExpressionFunctionProvider;
use Sylius\Resource\Symfony\ExpressionLanguage\RequestVariables;
use Sylius\Resource\Symfony\ExpressionLanguage\SyliusRepositoriesVariables;
use Sylius\Resource\Symfony\ExpressionLanguage\TokenVariables;
use Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection;
use Sylius\Resource\Symfony\ExpressionLanguage\VarsResolver;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

return static function (ContainerConfigurator $container) {
    $services = $container->services();

    $services->set('sylius.metadata.expression_language', ExpressionLanguage::class);

    $services->set('sylius.resource_factory.expression_language', ExpressionLanguage::class);

    $services->set('sylius.repository.expression_language', ExpressionLanguage::class);

    $services->set('sylius.routing.expression_language', ExpressionLanguage::class);

    $services->set('sylius.expression_language.variables.token', TokenVariables::class)
        ->args([service('security.token_storage')->nullOnInvalid()])
        ->tag('sylius.metadata_variables')
        ->tag('sylius.resource_factory_variables')
        ->tag('sylius.repository_variables');

    $services->set('sylius.expression_language.variables.request', RequestVariables::class)
        ->args([service('request_stack')])
        ->tag('sylius.metadata_variables')
        ->tag('sylius.resource_factory_variables')
        ->tag('sylius.repository_variables')
        ->tag('sylius.routing_variables');

    $services->set('sylius.expression_language.variables.sylius_repositories', SyliusRepositoriesVariables::class)
        ->args([tagged_locator('sylius.repository')])
        ->tag('sylius.metadata_variables')
        ->tag('sylius.resource_factory_variables');

    $services->set('sylius.expression_language.variables_collection.metadata', VariablesCollection::class)
        ->args([tagged_iterator('sylius.metadata_variables')]);

    $services->set('sylius.expression_language.variables_collection.factory', VariablesCollection::class)
        ->args([tagged_iterator('sylius.resource_factory_variables')]);

    $services->set('sylius.expression_language.variables_collection.form', VariablesCollection::class)
        ->args([tagged_iterator('sylius.form_variables')]);

    $services->set('sylius.expression_language.variables_collection.repository', VariablesCollection::class)
        ->args([tagged_iterator('sylius.repository_variables')]);

    $services->set('sylius.expression_language.variables_collection.routing', VariablesCollection::class)
        ->args([tagged_iterator('sylius.routing_variables')]);

    $services->set('sylius.expression_language.providers.throw_not_found_on_null', ThrowNotFoundOnNullExpressionFunctionProvider::class)
        ->tag('sylius.metadata_providers')
        ->tag('sylius.resource_factory_providers');

    $services->set('sylius.expression_language.vars_resolver.metadata', VarsResolver::class)
        ->args([service('sylius.expression_language.argument_parser.metadata')]);

    $services->set('sylius.expression_language.argument_parser.metadata', ArgumentParser::class)
        ->args([
            service('sylius.metadata.expression_language'),
            service('sylius.expression_language.variables_collection.metadata'),
            tagged_iterator('sylius.metadata_providers'),
        ]);

    $services->set('sylius.expression_language.argument_parser.factory', ArgumentParser::class)
        ->args([
            service('sylius.resource_factory.expression_language'),
            service('sylius.expression_language.variables_collection.factory'),
            tagged_iterator('sylius.resource_factory_providers'),
        ]);

    $services->set('sylius.expression_language.argument_parser.form', ArgumentParser::class)
        ->args([
            service('sylius.resource_factory.expression_language'),
            service('sylius.expression_language.variables_collection.form'),
            tagged_iterator('sylius.resource_factory_providers'),
        ]);

    $services->set('sylius.expression_language.argument_parser.repository', ArgumentParser::class)
        ->args([
            service('sylius.repository.expression_language'),
            service('sylius.expression_language.variables_collection.repository'),
            tagged_iterator('sylius.repository_providers'),
        ]);

    $services->set('sylius.expression_language.argument_parser.routing', ArgumentParser::class)
        ->args([
            service('sylius.routing.expression_language'),
            service('sylius.expression_language.variables_collection.routing'),
            tagged_iterator('sylius.routing_providers'),
        ]);
};
