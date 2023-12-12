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

namespace Sylius\Bundle\ResourceBundle\Form\DataTransformer;

use Sylius\Resource\Doctrine\Peristence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Webmozart\Assert\Assert;

final class ResourceToIdentifierTransformer implements DataTransformerInterface
{
    private RepositoryInterface $repository;

    private string $identifier;

    public function __construct(RepositoryInterface $repository, ?string $identifier = null)
    {
        $this->repository = $repository;
        $this->identifier = $identifier ?? 'id';
    }

    /**
     * @psalm-suppress MissingParamType
     *
     * @param object|null $value
     *
     * @return mixed
     */
    public function transform($value)
    {
        if (null === $value) {
            return null;
        }

        /** @psalm-suppress ArgumentTypeCoercion */
        Assert::isInstanceOf($value, $this->repository->getClassName());

        return PropertyAccess::createPropertyAccessor()->getValue($value, $this->identifier);
    }

    /** @param int|string|null $value */
    public function reverseTransform($value): ?ResourceInterface
    {
        if (null === $value) {
            return null;
        }

        /** @var ResourceInterface|null $resource */
        $resource = $this->repository->findOneBy([$this->identifier => $value]);
        if (null === $resource) {
            throw new TransformationFailedException(sprintf(
                'Object "%s" with identifier "%s"="%s" does not exist.',
                $this->repository->getClassName(),
                $this->identifier,
                $value,
            ));
        }

        return $resource;
    }
}
