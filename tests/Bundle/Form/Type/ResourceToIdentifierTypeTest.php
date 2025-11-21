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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\Type;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\DataTransformer\ResourceToIdentifierTransformer;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceToIdentifierType;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ResourceToIdentifierTypeTest extends TestCase
{
    /** @var RepositoryInterface<ResourceInterface>&MockObject */
    private RepositoryInterface $repository;

    /** @var MetadataInterface&MockObject */
    private MetadataInterface $metadata;

    private ResourceToIdentifierType $formType;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(RepositoryInterface::class);
        $this->metadata = $this->createMock(MetadataInterface::class);
        $this->formType = new ResourceToIdentifierType($this->repository, $this->metadata);
    }

    public function testExtendsAbstractType(): void
    {
        self::assertInstanceOf(AbstractType::class, $this->formType);
    }

    public function testGetParent(): void
    {
        self::assertSame(EntityType::class, $this->formType->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        $this->metadata
            ->expects(self::once())
            ->method('getApplicationName')
            ->willReturn('app');

        $this->metadata
            ->expects(self::once())
            ->method('getName')
            ->willReturn('product');

        self::assertSame('app_product_to_identifier', $this->formType->getBlockPrefix());
    }

    public function testConfigureOptionsWithDefaults(): void
    {
        $resolver = new OptionsResolver();

        $this->formType->configureOptions($resolver);

        $options = $resolver->resolve();

        self::assertArrayHasKey('identifier', $options);
        self::assertSame('id', $options['identifier']);
    }

    public function testConfigureOptionsWithCustomIdentifier(): void
    {
        $resolver = new OptionsResolver();

        $this->formType->configureOptions($resolver);

        $options = $resolver->resolve(['identifier' => 'uuid']);

        self::assertSame('uuid', $options['identifier']);
    }

    public function testConfigureOptionsThrowsExceptionForInvalidIdentifierType(): void
    {
        $resolver = new OptionsResolver();

        $this->formType->configureOptions($resolver);

        self::expectException(InvalidOptionsException::class);

        $resolver->resolve(['identifier' => 123]);
    }

    public function testBuildFormAddsResourceToIdentifierTransformer(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('addModelTransformer')
            ->with(self::isInstanceOf(ResourceToIdentifierTransformer::class));

        $this->formType->buildForm($builder, ['identifier' => 'id']);
    }

    public function testBuildFormAddsTransformerWithCustomIdentifier(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('addModelTransformer')
            ->with(self::callback(function (ResourceToIdentifierTransformer $transformer): bool {
                return $transformer instanceof ResourceToIdentifierTransformer;
            }));

        $this->formType->buildForm($builder, ['identifier' => 'uuid']);
    }
}
