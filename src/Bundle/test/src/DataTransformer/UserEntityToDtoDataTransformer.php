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

namespace App\DataTransformer;

use App\Dto\User;
use App\Entity\User as UserEntity;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\DataTransformer\DataTransformerInterface;
use Webmozart\Assert\Assert;

final class UserEntityToDtoDataTransformer implements DataTransformerInterface
{
    public function transform(object $data, string $to, RequestConfiguration $configuration): User
    {
        Assert::isInstanceOf($data, UserEntity::class);

        return new User($data->getUsername() ?: '');
    }

    public function supportsTransformation(object $data, string $to, RequestConfiguration $configuration): bool
    {
        return $data instanceof UserEntity && is_a($to, User::class, true);
    }
}
