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

namespace Sylius\Component\Resource\Tests\Dummy\Nested;

use Sylius\Component\Resource\Annotation\SyliusResource;
use Sylius\Component\Resource\Model\ResourceInterface;

#[SyliusResource(
    name: 'app.dummy_class_three',
    model: self::class,
)]
final class DummyClassThree implements ResourceInterface
{
    public function getId(): string
    {
        return 'dummy';
    }
}
