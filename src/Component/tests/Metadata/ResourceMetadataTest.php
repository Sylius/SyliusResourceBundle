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

namespace Sylius\Resource\Tests\Metadata;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Update;

final class ResourceMetadataTest extends TestCase
{
    use ProphecyTrait;

    private ResourceMetadata $resourceMetadata;

    protected function setUp(): void
    {
        $this->resourceMetadata = new ResourceMetadata();
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(ResourceMetadata::class, $this->resourceMetadata);
    }

    public function testItHasNoAliasByDefault(): void
    {
        $this->assertNull($this->resourceMetadata->getAlias());
    }

    public function testItCouldHaveAnAlias(): void
    {
        $this->resourceMetadata = $this->resourceMetadata->withAlias('app.book');
        $this->assertSame('app.book', $this->resourceMetadata->getAlias());
    }

    public function testItHasNoSectionByDefault(): void
    {
        $this->assertNull($this->resourceMetadata->getSection());
    }

    public function testItCouldHaveASection(): void
    {
        $this->resourceMetadata = $this->resourceMetadata->withSection('admin');
        $this->assertSame('admin', $this->resourceMetadata->getSection());
    }

    public function testItHasNoNameByDefault(): void
    {
        $this->assertNull($this->resourceMetadata->getName());
    }

    public function testItCouldHaveAName(): void
    {
        $this->resourceMetadata = $this->resourceMetadata->withName('book');
        $this->assertSame('book', $this->resourceMetadata->getName());
    }

    public function testItHasNoApplicationNameByDefault(): void
    {
        $this->assertNull($this->resourceMetadata->getApplicationName());
    }

    public function testItCouldHaveAnApplicationName(): void
    {
        $this->resourceMetadata = $this->resourceMetadata->withApplicationName('app');
        $this->assertSame('app', $this->resourceMetadata->getApplicationName());
    }

    public function testItHasNoOperationsByDefault(): void
    {
        $this->assertNull($this->resourceMetadata->getOperations());
    }

    public function testItCouldHaveOperations(): void
    {
        $operations = new Operations();
        $this->resourceMetadata = $this->resourceMetadata->withOperations($operations);
        $this->assertSame($operations, $this->resourceMetadata->getOperations());
    }

    public function testItCanBeConstructedWithAnAlias(): void
    {
        $this->resourceMetadata = new ResourceMetadata('app.book');
        $this->assertSame('app.book', $this->resourceMetadata->getAlias());
    }

    public function testItCanBeConstructedWithASection(): void
    {
        $this->resourceMetadata = new ResourceMetadata(null, 'admin');
        $this->assertSame('admin', $this->resourceMetadata->getSection());
    }

    public function testItCanBeConstructedWithAName(): void
    {
        $this->resourceMetadata = new ResourceMetadata(name: 'book');
        $this->assertSame('book', $this->resourceMetadata->getName());
    }

    public function testItCanBeConstructedWithAnApplicationName(): void
    {
        $this->resourceMetadata = new ResourceMetadata(applicationName: 'app');
        $this->assertSame('app', $this->resourceMetadata->getApplicationName());
    }

    public function testItCanBeConstructedWithAFormType(): void
    {
        $this->resourceMetadata = new ResourceMetadata(formType: 'App\Form\DummyType');
        $this->assertSame('App\Form\DummyType', $this->resourceMetadata->getFormType());
    }

    public function testItCanBeConstructedWithATemplatesDir(): void
    {
        $this->resourceMetadata = new ResourceMetadata(templatesDir: 'book');
        $this->assertSame('book', $this->resourceMetadata->getTemplatesDir());
    }

    public function testItCanBeConstructedWithARoutePrefix(): void
    {
        $this->resourceMetadata = new ResourceMetadata(routePrefix: '/admin');
        $this->assertSame('/admin', $this->resourceMetadata->getRoutePrefix());
    }

    public function testItCanBeConstructedWithAPluralName(): void
    {
        $this->resourceMetadata = new ResourceMetadata(pluralName: 'books');
        $this->assertSame('books', $this->resourceMetadata->getPluralName());
    }

    public function testItCanBeConstructedWithAnIdentifier(): void
    {
        $this->resourceMetadata = new ResourceMetadata(identifier: 'code');
        $this->assertSame('code', $this->resourceMetadata->getIdentifier());
    }

    public function testItCanBeConstructedWithANormalizationContext(): void
    {
        $this->resourceMetadata = new ResourceMetadata(
            normalizationContext: ['groups' => ['dummy:read']],
        );
        $this->assertSame(['groups' => ['dummy:read']], $this->resourceMetadata->getNormalizationContext());
    }

    public function testItCanBeConstructedWithADenormalizationContext(): void
    {
        $this->resourceMetadata = new ResourceMetadata(
            denormalizationContext: ['groups' => ['dummy:write']],
        );
        $this->assertSame(['groups' => ['dummy:write']], $this->resourceMetadata->getDenormalizationContext());
    }

    public function testItCanBeConstructedWithAValidationContext(): void
    {
        $this->resourceMetadata = new ResourceMetadata(
            validationContext: ['groups' => ['sylius']],
        );
        $this->assertSame(['groups' => ['sylius']], $this->resourceMetadata->getValidationContext());
    }

    public function testItCanBeConstructedWithAClass(): void
    {
        $this->resourceMetadata = new ResourceMetadata(
            class: 'App\Resource',
        );
        $this->assertSame('App\Resource', $this->resourceMetadata->getClass());
    }

    public function testItCanBeConstructedWithADriver(): void
    {
        $this->resourceMetadata = new ResourceMetadata(
            driver: 'doctrine/orm',
        );
        $this->assertSame('doctrine/orm', $this->resourceMetadata->getDriver());
    }

    public function testItCanBeConstructedWithVars(): void
    {
        $this->resourceMetadata = new ResourceMetadata(
            vars: ['subheader' => 'Managing your library'],
        );
        $this->assertSame(['subheader' => 'Managing your library'], $this->resourceMetadata->getVars());
    }

    public function testItCanBeConstructedWithOperations(): void
    {
        $operations = [new Create(), new Update()];
        $this->resourceMetadata = new ResourceMetadata(
            operations: $operations,
        );
        $this->assertCount(2, $this->resourceMetadata->getOperations());
    }
}
