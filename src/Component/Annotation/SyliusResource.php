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
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType;
use Sylius\Bundle\ResourceBundle\SyliusResourceBundle;
use Sylius\Component\Resource\Factory\Factory;
use Sylius\Component\Resource\Model\ResourceInterface;
use Webmozart\Assert\Assert;

#[Attribute(Attribute::TARGET_CLASS)]
final class SyliusResource
{
    public function __construct(
        public string $name,
        public string $model,
        public string $controller = ResourceController::class,
        public string $factory = Factory::class,
        public string $form = DefaultResourceType::class,
        public string $repository = EntityRepository::class,
        public string $driver = SyliusResourceBundle::DRIVER_DOCTRINE_ORM,
        public ?string $translationModel = null,
        public string $translationController = ResourceController::class,
        public string $translationFactory = Factory::class,
        public string $translationForm = DefaultResourceType::class,
    ) {
        Assert::allClassExists([$this->model, $this->controller, $this->factory, $this->form, $this->repository]);
        Assert::isAOf($model, ResourceInterface::class, sprintf('"%s" is not a valid resource model class.', $model));

        if ($this->translationModel) {
            Assert::allClassExists([
                $this->translationModel,
                $this->translationController,
                $this->translationFactory,
                $this->translationForm
            ]);
        }
    }

    public function asArray(): array
    {
        $result = [];
        $result[$this->name] = [
            'classes' => [
                'model' => $this->model,
                'controller' => $this->controller,
                'factory' => $this->factory,
                'form' => $this->form,
                'repository' => $this->repository,
            ],
            'driver' => $this->driver,
        ];

        if ($this->translationModel) {
            $result[$this->name]['translation']['classes'] = [
                'model' => $this->translationModel,
                'controller' => $this->translationController,
                'factory' => $this->translationFactory,
                'form' => $this->translationForm,
            ];
        }

        return $result;
    }
}
