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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ORM\Handler;

use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Controller\ResourceDeleteHandlerInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Exception\DeleteHandlingException;
use Sylius\Resource\Model\ResourceInterface;

final class ResourceDeleteHandler implements ResourceDeleteHandlerInterface
{
    public function __construct(
        private ResourceDeleteHandlerInterface $decoratedHandler,
        private ManagerRegistry $managerRegistry,
    ) {
    }

    /**
     * @param RepositoryInterface<ResourceInterface> $repository
     *
     * @throws DeleteHandlingException
     */
    public function handle(ResourceInterface $resource, RepositoryInterface $repository): void
    {
        $entityManager = $this->managerRegistry->getManagerForClass($resource::class);

        if (!$entityManager instanceof EntityManagerInterface) {
            $this->decoratedHandler->handle($resource, $repository);

            return;
        }

        $entityManager->beginTransaction();

        try {
            $this->decoratedHandler->handle($resource, $repository);

            $entityManager->commit();
        } catch (ForeignKeyConstraintViolationException $exception) {
            $entityManager->rollback();

            throw new DeleteHandlingException(
                'Cannot delete, the resource is in use.',
                'delete_error',
                409,
                0,
                $exception,
            );
        } catch (\Throwable $exception) {
            $entityManager->rollback();

            throw $exception;
        }
    }
}
