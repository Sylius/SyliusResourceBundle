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

namespace Sylius\Bundle\ResourceBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class CsrfParameterExtension extends AbstractExtension
{
    public function __construct(private readonly string $csrfParameter)
    {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_csrf_parameter', $this->getCsrfParameter(...)),
        ];
    }

    public function getCsrfParameter(): string
    {
        return $this->csrfParameter;
    }
}
