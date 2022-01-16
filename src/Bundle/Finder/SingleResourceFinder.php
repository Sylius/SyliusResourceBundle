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

namespace Sylius\Bundle\ResourceBundle\Finder;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Controller\SingleResourceProviderInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class SingleResourceFinder implements SingleResourceFinderInterface
{
    private SingleResourceProviderInterface $singleResourceProvider;

    public function __construct(SingleResourceProviderInterface $singleResourceProvider)
    {
        $this->singleResourceProvider = $singleResourceProvider;
    }

    public function findOr404(
        RequestConfiguration $configuration,
        RepositoryInterface $repository,
        string $resourceName
    ): ResourceInterface {
        $resource = $this->singleResourceProvider->get($configuration, $repository);
        if (null === $resource) {
            throw new NotFoundHttpException(sprintf('The "%s" has not been found', $resourceName));
        }

        return $resource;
    }
}
