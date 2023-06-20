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

namespace App\Entity\Route;

use App\Entity\User;
use App\Form\Type\RegisterType;
use Sylius\Component\Resource\Annotation\SyliusRoute;

#[SyliusRoute(
    name: 'register_user_with_form',
    path: '/users/register',
    methods: ['GET', 'POST'],
    controller: 'app.controller.user:createAction',
    form: RegisterType::class,
)]
final class RegisterUserWithForm extends User
{
}
