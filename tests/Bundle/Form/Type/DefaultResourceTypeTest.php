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
use Sylius\Bundle\ResourceBundle\Form\Builder\DefaultFormBuilderInterface;
use Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Metadata\RegistryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Webmozart\Assert\InvalidArgumentException;

final class DefaultResourceTypeTest extends TestCase
{
    /** @var RegistryInterface&MockObject */
    private RegistryInterface $metadataRegistry;

    /** @var ServiceRegistryInterface&MockObject */
    private ServiceRegistryInterface $formBuilderRegistry;

    private DefaultResourceType $formType;

    protected function setUp(): void
    {
        $this->metadataRegistry = $this->createMock(RegistryInterface::class);
        $this->formBuilderRegistry = $this->createMock(ServiceRegistryInterface::class);
        $this->formType = new DefaultResourceType($this->metadataRegistry, $this->formBuilderRegistry);
    }

    public function testExtendsAbstractType(): void
    {
        self::assertInstanceOf(AbstractType::class, $this->formType);
    }

    public function testGetBlockPrefix(): void
    {
        self::assertSame('sylius_resource', $this->formType->getBlockPrefix());
    }

    public function testBuildFormGetsMetadataByClass(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata
            ->method('getDriver')
            ->willReturn('doctrine/orm');

        $this->metadataRegistry
            ->expects(self::once())
            ->method('getByClass')
            ->with('App\Entity\Product')
            ->willReturn($metadata);

        $formBuilder = $this->createMock(DefaultFormBuilderInterface::class);
        $this->formBuilderRegistry
            ->method('get')
            ->willReturn($formBuilder);

        $builder = $this->createMock(FormBuilderInterface::class);

        $this->formType->buildForm($builder, ['data_class' => 'App\Entity\Product']);
    }

    public function testBuildFormGetsFormBuilderByDriver(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata
            ->method('getDriver')
            ->willReturn('doctrine/orm');

        $this->metadataRegistry
            ->method('getByClass')
            ->willReturn($metadata);

        $formBuilder = $this->createMock(DefaultFormBuilderInterface::class);
        $this->formBuilderRegistry
            ->expects(self::once())
            ->method('get')
            ->with('doctrine/orm')
            ->willReturn($formBuilder);

        $builder = $this->createMock(FormBuilderInterface::class);

        $this->formType->buildForm($builder, ['data_class' => 'App\Entity\Product']);
    }

    public function testBuildFormCallsFormBuilderBuild(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata
            ->method('getDriver')
            ->willReturn('doctrine/orm');

        $this->metadataRegistry
            ->method('getByClass')
            ->willReturn($metadata);

        $formBuilder = $this->createMock(DefaultFormBuilderInterface::class);
        $builder = $this->createMock(FormBuilderInterface::class);
        $options = ['data_class' => 'App\Entity\Product', 'foo' => 'bar'];

        $formBuilder
            ->expects(self::once())
            ->method('build')
            ->with($metadata, $builder, $options);

        $this->formBuilderRegistry
            ->method('get')
            ->willReturn($formBuilder);

        $this->formType->buildForm($builder, $options);
    }

    public function testBuildFormThrowsExceptionWhenNoDriver(): void
    {
        $metadata = $this->createMock(MetadataInterface::class);
        $metadata
            ->method('getDriver')
            ->willReturn(false);
        $metadata
            ->method('getAlias')
            ->willReturn('app.product');

        $this->metadataRegistry
            ->method('getByClass')
            ->willReturn($metadata);

        $builder = $this->createMock(FormBuilderInterface::class);

        self::expectException(InvalidArgumentException::class);
        self::expectExceptionMessage('Form "Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType" cannot be used with no driver configured on the resource "app.product". Please define a form.');

        $this->formType->buildForm($builder, ['data_class' => 'App\Entity\Product']);
    }

    public function testBuildFormThrowsExceptionWhenDataClassIsNotString(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);

        self::expectException(InvalidArgumentException::class);

        $this->formType->buildForm($builder, ['data_class' => null]);
    }
}
