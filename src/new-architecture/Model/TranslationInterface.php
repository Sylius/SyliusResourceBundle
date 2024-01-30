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

namespace Sylius\Resource\Model;

interface TranslationInterface
{
    public function getTranslatable(): TranslatableInterface;

    public function setTranslatable(?TranslatableInterface $translatable): void;

    public function getLocale(): ?string;

    public function setLocale(?string $locale): void;
}

class_alias(TranslationInterface::class, \Sylius\Component\Resource\Model\TranslationInterface::class);
