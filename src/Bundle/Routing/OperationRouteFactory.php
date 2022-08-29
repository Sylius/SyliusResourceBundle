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

        if (null !== $section = $operation->getSection()) {
            return sprintf('%s/%s', $section, $defaultPath);
        }

        return $defaultPath;
    }

    private function getDefaultRoutePathForOperation(MetadataInterface $metadata, Operation $operation): string
    {
        $rootPath = sprintf('%s', Urlizer::urlize($metadata->getPluralName()));

        $action = $operation->getAction();

        if ('index' === $action) {
            return sprintf('%s', $rootPath);
        }

        if ('create' === $action) {
            return sprintf('%s/new', $rootPath);
        }

        if ('update' === $action) {
            return sprintf('%s/{id}/edit', $rootPath);
        }

        if ('delete' === $action) {
            return sprintf('%s/{id}', $rootPath);
        }

        if ('show' === $action) {
            return sprintf('%s/{id}', $rootPath);
        }

        throw new \InvalidArgumentException(sprintf('Impossible to get a default route path for this route with action "%s". Please define a path.', $action ?? ''));
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

        if (null !== $action = $operation->getAction()) {
            $syliusOptions['operation'] = $action;
        }
//
//        if (isset($arguments['provider'])) {
//            $syliusOptions['provider'] = $arguments['provider'];
//        }
//
//        if (isset($arguments['processor'])) {
//            $syliusOptions['processor'] = $arguments['processor'];
//        }
//
        if (null !== $read = $operation->isRead()) {
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
//        if (isset($arguments['serializationGroups'])) {
//            $syliusOptions['serialization_groups'] = $arguments['serializationGroups'];
//        }
//
//        if (isset($arguments['serializationVersion'])) {
//            $syliusOptions['serialization_version'] = $arguments['serializationVersion'];
//        }
//
//        if (isset($arguments['form'])) {
//            $syliusOptions['form'] = $arguments['form'];
//        }
//
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
//        if (isset($arguments['stateMachine'])) {
//            $syliusOptions['state_machine'] = $arguments['stateMachine'];
//        }
//
//        if (isset($arguments['event'])) {
//            $syliusOptions['event'] = $arguments['event'];
//        }
//
//        if (isset($arguments['returnContent'])) {
//            $syliusOptions['return_content'] = $arguments['returnContent'];
//        }

        return $syliusOptions;
    }
}
