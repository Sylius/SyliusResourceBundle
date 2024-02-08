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

namespace Sylius\Bundle\ResourceBundle\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Webmozart\Assert\Assert;

final class DefaultResourceType extends AbstractType
{
    private RegistryInterface $metadataRegistry;

    private ServiceRegistryInterface $formBuilderRegistry;

    public function __construct(RegistryInterface $metadataRegistry, ServiceRegistryInterface $formBuilderRegistry)
    {
        $this->metadataRegistry = $metadataRegistry;
        $this->formBuilderRegistry = $formBuilderRegistry;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        Assert::string($options['data_class']);

        $metadata = $this->metadataRegistry->getByClass($options['data_class']);

        $driver = $metadata->getDriver();

        Assert::notFalse($driver, sprintf(
            'Form "%s" cannot be used with no driver configured on the resource "%s". Please define a form.',
            __CLASS__,
            $metadata->getAlias(),
        ));

        /** @var DefaultFormBuilderInterface $formBuilder */
        $formBuilder = $this->formBuilderRegistry->get($driver);

        $formBuilder->build($metadata, $builder, $options);
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_resource';
    }
}
