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

namespace App\Conference\Factory;

use App\Conference\Entity\Speaker;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Speaker>
 */
final class SpeakerFactory extends PersistentProxyObjectFactory
{
    public static function class(): string
    {
        return Speaker::class;
    }

    public function withFirstName(string $firstName): self
    {
        return $this->with(['firstName' => $firstName]);
    }

    public function withLastName(string $lastName): self
    {
        return $this->with(['lastName' => $lastName]);
    }

    protected function defaults(): array
    {
        return [
            'firstName' => self::faker()->firstName(),
            'lastName' => self::faker()->lastName(),
        ];
    }
}
