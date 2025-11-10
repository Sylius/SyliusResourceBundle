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

return static function (ContainerConfigurator $container) {
    $services = $container->services();
    $parameters = $container->parameters();

    $services->set('sylius.metadata.expression_language', \Symfony\Component\ExpressionLanguage\ExpressionLanguage::class);

    $services->set('sylius.resource_factory.expression_language', \Symfony\Component\ExpressionLanguage\ExpressionLanguage::class);

    $services->set('sylius.repository.expression_language', \Symfony\Component\ExpressionLanguage\ExpressionLanguage::class);

    $services->set('sylius.routing.expression_language', \Symfony\Component\ExpressionLanguage\ExpressionLanguage::class);

    $services->set('sylius.expression_language.variables.token', 'Sylius\Resource\Symfony\ExpressionLanguage\TokenVariables')
        ->args([service('security.token_storage')->nullOnInvalid()])
        ->tag('sylius.metadata_variables')
        ->tag('sylius.resource_factory_variables')
        ->tag('sylius.repository_variables');

    $services->set('sylius.expression_language.variables.request', 'Sylius\Resource\Symfony\ExpressionLanguage\RequestVariables')
        ->args([service('request_stack')])
        ->tag('sylius.metadata_variables')
        ->tag('sylius.resource_factory_variables')
        ->tag('sylius.repository_variables')
        ->tag('sylius.routing_variables');

    $services->set('sylius.expression_language.variables.sylius_repositories', 'Sylius\Resource\Symfony\ExpressionLanguage\SyliusRepositoriesVariables')
        ->args([tagged_locator('sylius.repository')])
        ->tag('sylius.metadata_variables')
        ->tag('sylius.resource_factory_variables');

    $services->set('sylius.expression_language.variables_collection.metadata', 'Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection')
        ->args([tagged_iterator('sylius.metadata_variables')]);

    $services->set('sylius.expression_language.variables_collection.factory', 'Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection')
        ->args([tagged_iterator('sylius.resource_factory_variables')]);

    $services->set('sylius.expression_language.variables_collection.repository', 'Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection')
        ->args([tagged_iterator('sylius.repository_variables')]);

    $services->set('sylius.expression_language.variables_collection.routing', 'Sylius\Resource\Symfony\ExpressionLanguage\VariablesCollection')
        ->args([tagged_iterator('sylius.routing_variables')]);

    $services->set('sylius.expression_language.providers.throw_not_found_on_null', 'Sylius\Resource\Symfony\ExpressionLanguage\Provider\ThrowNotFoundOnNullExpressionFunctionProvider')
        ->tag('sylius.metadata_providers')
        ->tag('sylius.resource_factory_providers');

    $services->set('sylius.expression_language.vars_resolver.metadata', 'Sylius\Resource\Symfony\ExpressionLanguage\VarsResolver')
        ->args([service('sylius.expression_language.argument_parser.metadata')]);

    $services->set('sylius.expression_language.argument_parser.metadata', 'Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser')
        ->args([
            service('sylius.metadata.expression_language'),
            service('sylius.expression_language.variables_collection.metadata'),
            tagged_iterator('sylius.metadata_providers'),
        ]);

    $services->set('sylius.expression_language.argument_parser.factory', 'Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser')
        ->args([
            service('sylius.resource_factory.expression_language'),
            service('sylius.expression_language.variables_collection.factory'),
            tagged_iterator('sylius.resource_factory_providers'),
        ]);

    $services->set('sylius.expression_language.argument_parser.repository', 'Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser')
        ->args([
            service('sylius.repository.expression_language'),
            service('sylius.expression_language.variables_collection.repository'),
            tagged_iterator('sylius.repository_providers'),
        ]);

    $services->set('sylius.expression_language.argument_parser.routing', 'Sylius\Resource\Symfony\ExpressionLanguage\ArgumentParser')
        ->args([
            service('sylius.routing.expression_language'),
            service('sylius.expression_language.variables_collection.routing'),
            tagged_iterator('sylius.routing_providers'),
        ]);
};
