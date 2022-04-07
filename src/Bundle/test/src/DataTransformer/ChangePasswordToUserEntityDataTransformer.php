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

use App\Dto\ChangePassword;
use App\Entity\User;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\DataTransformer\DataTransformerInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webmozart\Assert\Assert;

final class ChangePasswordToUserEntityDataTransformer implements DataTransformerInterface
{
    private RequestStack $requestStack;

    private RepositoryInterface $userRepository;

    public function __construct(RequestStack $requestStack, RepositoryInterface $userRepository)
    {
        $this->requestStack = $requestStack;
        $this->userRepository = $userRepository;
    }

    public function transform(object $data, string $to, RequestConfiguration $configuration): User
    {
        $id = $this->requestStack->getCurrentRequest()->attributes->get('id');
        Assert::notNull($id, 'No id was found on request');

        /** @var User|null $user */
        $user = $this->userRepository->find($id);

        if (null === $user) {
            throw new NotFoundHttpException('No user was found.');
        }

        $user->setPassword($data->password);

        return $user;
    }

    public function supportsTransformation(object $data, string $to, RequestConfiguration $configuration): bool
    {
        return $data instanceof ChangePassword && is_a($to, User::class, true);
    }
}
