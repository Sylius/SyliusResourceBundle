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

use App\Dto\Register;
use App\Entity\User;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\DataTransformer\DataTransformerInterface;
use Webmozart\Assert\Assert;

final class RegisterToUserDataTransformer implements DataTransformerInterface
{
    public function transform(object $data, string $to, RequestConfiguration $configuration): User
    {
        Assert::isInstanceOf($data, Register::class);

        $user = new User();
        $user->setUsername($data->username);
        $user->setPassword($data->password);

        return $user;
    }

    public function supportsTransformation(object $data, string $to, RequestConfiguration $configuration): bool
    {
        return $data instanceof Register && is_a($to, User::class, true);
    }
}
