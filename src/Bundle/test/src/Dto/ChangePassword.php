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

namespace App\Dto;

final class ChangePassword
{
    public ?string $password = null;

    public function __construct(?string $password = null)
    {
        $this->password = $password;
    }
}
