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

use App\Dto\UpdateProfile;
use App\Entity\User;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\DataTransformer\DataTransformerInterface;
use Webmozart\Assert\Assert;

final class UserToUpdateProfileDataTransformer implements DataTransformerInterface
{
    public function transform(object $data, string $to, RequestConfiguration $configuration): UpdateProfile
    {
        Assert::isInstanceOf($data, User::class);

        return new UpdateProfile($data->getUsername());
    }

    public function supportsTransformation(object $data, string $to, RequestConfiguration $configuration): bool
    {
        return $data instanceof User && is_a($to, UpdateProfile::class, true);
    }
}
