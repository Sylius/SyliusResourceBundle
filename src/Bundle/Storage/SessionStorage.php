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

namespace Sylius\Bundle\ResourceBundle\Storage;

use Sylius\Component\Resource\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionStorage implements StorageInterface
{
    /** @var SessionInterface */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function has(string $name): bool
    {
        return $this->session->has($name);
    }

    public function get(string $name, $default = null)
    {
        return $this->session->get($name, $default);
    }

    public function set(string $name, $value): void
    {
        $this->session->set($name, $value);
    }

    public function remove(string $name): void
    {
        $this->session->remove($name);
    }

    public function all(): array
    {
        return $this->session->all();
    }
}
