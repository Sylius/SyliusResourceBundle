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

namespace Sylius\Component\Resource\Annotation\SyliusResource;

use Sylius\Component\Resource\Model\TranslationInterface;
use Webmozart\Assert\Assert;

final class Translation
{
    public function __construct(
        private string $model,
        public ?string $controller = null,
        public ?string $factory = null,
        public ?string $form = null,
    ) {
        Assert::classExists($model);
        Assert::isAOf($model, TranslationInterface::class, sprintf('"%s" is not a valid translation model class.', $model));
        Assert::nullOrClassExists(
            $factory,
            sprintf('Translation\'s factory class "%s" does not exist.', $factory)
        );
        Assert::nullOrClassExists(
            $controller,
            sprintf('Translation\'s controller class "%s" does not exist.', $controller)
        );
        Assert::nullOrClassExists(
            $form,
            sprintf('Translation\'s form type class "%s" does not exist.', $form)
        );
    }

    public function asArray(): array
    {
        $result = [];
        $result['classes']['model'] = $this->model;

        if (null !== $this->controller) {
            $result['classes']['controller'] = $this->controller;
        }

        if (null !== $this->factory) {
            $result['classes']['factory'] = $this->factory;
        }

        if (null !== $this->form) {
            $result['classes']['form'] = $this->form;
        }

        return $result;
    }
}
