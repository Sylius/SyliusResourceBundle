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

namespace Sylius\Resource\Factory;

use Sylius\Component\Resource\Exception\UnexpectedTypeException;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Sylius\Resource\Model\TranslatableInterface;

final class TranslatableFactory implements TranslatableFactoryInterface
{
    private FactoryInterface $factory;

    private TranslationLocaleProviderInterface $localeProvider;

    public function __construct(FactoryInterface $factory, TranslationLocaleProviderInterface $localeProvider)
    {
        $this->factory = $factory;
        $this->localeProvider = $localeProvider;
    }

    /**
     * @throws UnexpectedTypeException
     */
    public function createNew()
    {
        $resource = $this->factory->createNew();

        if (!$resource instanceof TranslatableInterface) {
            throw new UnexpectedTypeException($resource, TranslatableInterface::class);
        }

        $resource->setCurrentLocale($this->localeProvider->getDefaultLocaleCode());
        $resource->setFallbackLocale($this->localeProvider->getDefaultLocaleCode());

        return $resource;
    }
}

class_alias(TranslatableFactory::class, \Sylius\Component\Resource\Factory\TranslatableFactory::class);
