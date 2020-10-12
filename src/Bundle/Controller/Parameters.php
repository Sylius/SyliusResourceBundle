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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Symfony\Component\HttpFoundation\ParameterBag;

class Parameters extends ParameterBag
{
    /**
     * {@inheritdoc}
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key, $default = null)
    {
        $result = parent::get($key, $default);

        if (null === $result && $default !== null && $this->has($key)) {
            $result = $default;
        }

        return $result;
    }
}
