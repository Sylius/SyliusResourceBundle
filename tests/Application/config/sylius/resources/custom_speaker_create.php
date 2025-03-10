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

use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operation;

return static function (Operation $operation): Operation {
    if (
        !$operation instanceof Create ||
        'app_admin_speaker_create' !== $operation->getName()
    ) {
        return $operation;
    }

    return $operation->withPath('speakers/register');
};
