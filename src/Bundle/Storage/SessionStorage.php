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
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class SessionStorage implements StorageInterface
{
    private ?SessionInterface $session;

    private RequestStack $requestStack;

    public function __construct(?SessionInterface $session, RequestStack $requestStack)
    {
        $this->session = $session;
        $this->requestStack = $requestStack;
    }

    public function has(string $name): bool
    {
        return $this->getSession()->has($name);
    }

    public function get(string $name, $default = null)
    {
        return $this->getSession()->get($name, $default);
    }

    public function set(string $name, $value): void
    {
        $this->getSession()->set($name, $value);
    }

    public function remove(string $name): void
    {
        $this->getSession()->remove($name);
    }

    public function all(): array
    {
        return $this->getSession()->all();
    }

    private function getSession(): SessionInterface
    {
        return $this->session ?: $this->requestStack->getSession();
    }
}
