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

namespace Sylius\Bundle\ResourceBundle\Doctrine\ORM;

use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Sylius\Component\Resource\Repository\RepositoryInterface;

/** @psalm-suppress DeprecatedInterface */
class EntityRepository extends BaseEntityRepository implements RepositoryInterface
{
    use ResourceRepositoryTrait;
}
