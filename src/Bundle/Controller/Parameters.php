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

namespace Sylius\Bundle\ResourceBundle\Controller;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpKernel\Kernel;

if (Kernel::MAJOR_VERSION >= 7) {
    class Parameters extends ParameterBag
    {
        /**
         * @param mixed $default
         */
        public function get(string $key, $default = null): mixed
        {
            $result = parent::get($key, $default);

            if (null === $result && $default !== null && $this->has($key)) {
                $result = $default;
            }

            return $result;
        }
    }
} else {
    class Parameters extends ParameterBag
    {
        /**
         * @param mixed $default
         *
         * @return mixed
         */
        public function get(string $key, $default = null)
        {
            $result = parent::get($key, $default);

            if (null === $result && $default !== null && $this->has($key)) {
                $result = $default;
            }

            return $result;
        }
    }
}
