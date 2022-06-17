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

namespace Sylius\Bundle\ResourceBundle\DependencyInjection\Driver\Exception;

class InvalidDriverException extends \Exception
{
    public function __construct(string $driver, string $className)
    {
        parent::__construct(sprintf(
            'Driver "%s" is not supported by %s.',
            $driver,
            $className,
        ));
    }
}
