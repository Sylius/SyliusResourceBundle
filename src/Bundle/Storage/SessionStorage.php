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
    /** @var RequestStack|SessionInterface */
    private $requestStack;

    /**
     * @param RequestStack|SessionInterface $requestStack
     */
    public function __construct(/* RequestStack */ $requestStack)
    {
        /** @phpstan-ignore-next-line */
        if (!$requestStack instanceof SessionInterface && !$requestStack instanceof RequestStack) {
            throw new \InvalidArgumentException(sprintf('The first argument of "%s" should be instance of "%s" or "%s"', __METHOD__, SessionInterface::class, RequestStack::class));
        }

        if ($requestStack instanceof SessionInterface) {
            @trigger_error(sprintf('Passing an instance of %s as constructor argument for %s is deprecated as of Sylius 1.9 and will be removed in 2.0. Pass an instance of %s instead.', SessionInterface::class, self::class, RequestStack::class), \E_USER_DEPRECATED);
        }

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
        if ($this->requestStack instanceof SessionInterface) {
            return $this->requestStack;
        }

        /** @phpstan-ignore-next-line */
        return $this->requestStack->getSession();
    }
}
