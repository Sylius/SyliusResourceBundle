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

namespace Sylius\Component\Resource\Annotation;

use Attribute;
use Sylius\Component\Resource\Annotation\SyliusResource\Translation;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

#[Attribute(Attribute::TARGET_CLASS)]
final class SyliusResource
{
    public function __construct(
        public string $name,
        public string $model,
        public ?string $controller = null,
        public ?string $factory = null,
        public ?string $form = null,
        public ?string $repository = null,
        public ?Translation $translation = null,
    ) {
        Assert::classExists($model);
        Assert::isAOf($model, ResourceInterface::class, sprintf('"%s" is not a valid resource model class.', $model));
        Assert::nullOrClassExists(
            $factory,
            sprintf('Factory class "%s" tried to be set for resource "%s" does not exist.', $factory, $name)
        );
        Assert::nullOrClassExists(
            $controller,
            sprintf('Controller class "%s" tried to be set for resource for resource "%s" does not exist.', $controller, $name)
        );
        Assert::nullOrClassExists(
            $repository,
            sprintf('Repository class "%s" tried to be set for resource for resource "%s" does not exist.', $repository, $name)
        );
        Assert::nullOrClassExists(
            $form,
            sprintf('Form type class "%s" tried to be set for resource for resource "%s" does not exist.', $form, $name)
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

        if (null !== $this->repository) {
            $result['classes']['repository'] = $this->repository;
        }

        if (null !== $this->translation) {
            $result['translation'] = $this->translation->asArray();
        }

        return $result;
    }
}
