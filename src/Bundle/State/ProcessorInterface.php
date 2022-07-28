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

namespace Sylius\Bundle\ResourceBundle\State;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;

/**
 * Process data: send an email, persist to storage, add to queue etc.
 *
 * @experimental
 */
interface ProcessorInterface
{
    /**
     * Handle the state.
     *
     * @return mixed
     */
    public function process(mixed $data, RequestConfiguration $configuration);
}
