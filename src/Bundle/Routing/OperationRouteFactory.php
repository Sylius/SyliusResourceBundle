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

namespace Sylius\Bundle\ResourceBundle\Routing;

use Gedmo\Sluggable\Util\Urlizer;
use Sylius\Component\Resource\Action\PlaceHolderAction;
use Sylius\Component\Resource\Metadata\MetadataInterface;
use Sylius\Component\Resource\Metadata\Operation;
use Symfony\Component\Routing\Route;
use Webmozart\Assert\Assert;

final class OperationRouteFactory implements OperationRouteFactoryInterface
{
    public function create(MetadataInterface $metadata, Operation $operation): Route
    {
        $routePath = $operation->getPath() ?? $this->getDefaultRoutePath($metadata, $operation);

        return new Route(
            $routePath,
            [
                '_controller' => $operation->getController() ?? PlaceHolderAction::class,
                '_sylius' => $this->getSyliusOptions($operation),
            ],
            $operation->getRequirements() ?? [],
            $operation->getOptions() ?? [],
            $operation->getHost() ?? '',
            $operation->getSchemes() ?? [],
            $operation->getMethods() ?? [],
        );
    }

    private function getDefaultRoutePath(MetadataInterface $metadata, Operation $operation): string
    {
        $defaultPath = $this->getDefaultRoutePathForOperation($metadata, $operation);

        if (null !== $routePrefix = $operation->getRoutePrefix()) {
            return sprintf('%s/%s', $routePrefix, $defaultPath);
        }

        return $defaultPath;
    }

    private function getDefaultRoutePathForOperation(MetadataInterface $metadata, Operation $operation): string
    {
        $rootPath = sprintf('%s', Urlizer::urlize($metadata->getPluralName()));

        $name = $operation->getName();
        Assert::notNull($name, 'Operation should have a name');

        if ('index' === $name) {
            return sprintf('%s', $rootPath);
        }

        if ('create' === $name) {
            return sprintf('%s/new', $rootPath);
        }

        if ('update' === $name) {
            return sprintf('%s/{id}/edit', $rootPath);
        }

        if ('delete' === $name) {
            return sprintf('%s/{id}', $rootPath);
        }

        if ('show' === $name) {
            return sprintf('%s/{id}', $rootPath);
        }

        throw new \InvalidArgumentException(sprintf('Impossible to get a default route path for this route with action "%s". Please define a path.', $name ?? ''));
    }

    private function getSyliusOptions(Operation $operation): array
    {
        $syliusOptions = [];

        if (null !== $template = $operation->getTemplate()) {
            $syliusOptions['template'] = $template;
        }

        if (null !== $vars = $operation->getVars()) {
            $syliusOptions['vars'] = $vars;
        }

        if (null !== $criteria = $operation->getCriteria()) {
            $syliusOptions['criteria'] = $criteria;
        }

        if (null !== $repository = $operation->getRepository()) {
            $syliusOptions['repository'] = $repository;
        }

        if (null !== $resource = $operation->getResource()) {
            $syliusOptions['resource'] = $resource;
        }

        if (null !== $name = $operation->getName()) {
            $syliusOptions['operation'] = $name;
        }

        if (null !== $provider = $operation->getProvider()) {
            $syliusOptions['provider'] = $provider;
        }

        if (null !== $processor = $operation->getProcessor()) {
            $syliusOptions['processor'] = $processor;
        }

        if (null !== $read = $operation->canRead()) {
            $syliusOptions['read'] = $read;
        }
//
//        if (isset($arguments['validate'])) {
//            $syliusOptions['validate'] = $arguments['validate'];
//        }
//
//        if (isset($arguments['write'])) {
//            $syliusOptions['write'] = $arguments['write'];
//        }
//
//        if (isset($arguments['respond'])) {
//            $syliusOptions['respond'] = $arguments['respond'];
//        }
//
        if (null !== $form = $operation->getForm()) {
            $syliusOptions['form'] = $form;
        }

        if (null !== $factory = $operation->getFactory()) {
            $syliusOptions['factory'] = $factory;
        }

        if (null !== $section = $operation->getSection()) {
            $syliusOptions['section'] = $section;
        }
//
//        if (isset($arguments['permission'])) {
//            $syliusOptions['permission'] = $arguments['permission'];
//        }
//
        if (null !== $grid = $operation->getGrid()) {
            $syliusOptions['grid'] = $grid;
        }
//
//        if (isset($arguments['csrfProtection'])) {
//            $syliusOptions['csrf_protection'] = $arguments['csrfProtection'];
//        }
//
//        if (isset($arguments['redirect'])) {
//            $syliusOptions['redirect'] = $arguments['redirect'];
//        }
//
        if (null !== $stateMachine = $operation->getStateMachine()) {
            $syliusOptions['state_machine'] = $stateMachine;
        }
//
//        if (isset($arguments['event'])) {
//            $syliusOptions['event'] = $arguments['event'];
//        }
//
//        if (isset($arguments['returnContent'])) {
//            $syliusOptions['return_content'] = $arguments['returnContent'];
//        }

        if (null !== $input = $operation->getInput()) {
            $syliusOptions['input'] = $input;
        }

        return $syliusOptions;
    }
}
