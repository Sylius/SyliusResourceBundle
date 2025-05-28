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

namespace App\Foundry\Factory;

use App\Entity\Author;
use Zenstruck\Foundry\ObjectFactory;

/**
 * @extends ObjectFactory<Author>
 */
final class AuthorFactory extends ObjectFactory
{
    public static function class(): string
    {
        return Author::class;
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
