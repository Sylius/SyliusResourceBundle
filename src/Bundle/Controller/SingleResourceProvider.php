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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Resource\Symfony\Request\RequestConfiguration;

final class SingleResourceProvider implements SingleResourceProviderInterface
{
    public function get(RequestConfiguration $requestConfiguration, RepositoryInterface $repository): ?ResourceInterface
    {
        $method = $requestConfiguration->getRepositoryMethod();
        if (null !== $method) {
            if (is_array($method) && 2 === count($method)) {
                $repository = $method[0];
                $method = $method[1];
            }

            $arguments = array_values($requestConfiguration->getRepositoryArguments());

            return $repository->$method(...$arguments);
        }

        $criteria = [];
        $request = $requestConfiguration->getRequest();

        if ($request->attributes->has('id')) {
            /** @var ResourceInterface|null $resource */
            $resource = $repository->find($request->attributes->get('id'));

            return $resource;
        }

        if ($request->attributes->has('slug')) {
            $criteria = ['slug' => $request->attributes->get('slug')];
        }

        $criteria = array_merge($criteria, $requestConfiguration->getCriteria());

        /** @var ResourceInterface|null $resource */
        $resource = $repository->findOneBy($criteria);

        return $resource;
    }
}
