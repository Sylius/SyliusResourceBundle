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

use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceAutocompleteChoiceType;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Sylius\Resource\Model\ResourceInterface;
use Symfony\Component\Form\PreloadedExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\Exception\InvalidOptionsException;
use Symfony\Component\OptionsResolver\Exception\MissingOptionsException;

final class ResourceAutocompleteChoiceTypeTest extends TypeTestCase
{
    private ServiceRegistryInterface|MockObject $resourceRepositoryRegistry;

    protected function setUp(): void
    {
        $this->resourceRepositoryRegistry = $this->createMock(ServiceRegistryInterface::class);

        parent::setUp();
    }

    protected function getExtensions(): array
    {
        $resourceAutoCompleteType = new ResourceAutocompleteChoiceType($this->resourceRepositoryRegistry);

        return [
            new PreloadedExtension([$resourceAutoCompleteType], []),
        ];
    }

    /**
     * @test
     */
    public function it_returns_resource_from_its_code(): void
    {
        /** @var MockObject|RepositoryInterface $resourceRepository */
        $resourceRepository = $this->createMock(RepositoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $this->resourceRepositoryRegistry->method('get')->with('sylius.resource')->willReturn($resourceRepository);
        $resourceRepository->method('findOneBy')->with(['code' => 'mug'])->willReturn($resource);

        $form = $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            null,
            ['resource' => 'sylius.resource', 'choice_name' => 'name', 'choice_value' => 'code'],
        );

        $form->submit('mug');

        $this->assertEquals($resource, $form->getData());
    }

    /**
     * @test
     */
    public function it_returns_resource_from_its_id(): void
    {
        /** @var MockObject|RepositoryInterface $resourceRepository */
        $resourceRepository = $this->createMock(RepositoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $this->resourceRepositoryRegistry->method('get')->with('sylius.resource')->willReturn($resourceRepository);
        $resourceRepository->method('findOneBy')->with(['id' => '1'])->willReturn($resource);

        $form = $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            null,
            ['resource' => 'sylius.resource', 'choice_name' => 'name', 'choice_value' => 'id'],
        );

        $form->submit('1');

        $this->assertEquals($resource, $form->getData());
    }

    /**
     * @test
     */
    public function it_returns_different_resource_from_its_identifier(): void
    {
        /** @var MockObject|RepositoryInterface $resourceRepository */
        $resourceRepository = $this->createMock(RepositoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $this->resourceRepositoryRegistry->method('get')->with('sylius.zone')->willReturn($resourceRepository);
        $resourceRepository->method('findOneBy')->with(['code' => 'eu'])->willReturn($resource);

        $form = $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            null,
            ['resource' => 'sylius.zone', 'choice_name' => 'name', 'choice_value' => 'code'],
        );

        $form->submit('eu');

        $this->assertEquals($resource, $form->getData());
    }

    /**
     * @test
     */
    public function it_has_identifier_as_view_value(): void
    {
        /** @var MockObject|RepositoryInterface $resourceRepository */
        $resourceRepository = $this->createMock(RepositoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $this->resourceRepositoryRegistry->method('get')->with('sylius.zone')->willReturn($resourceRepository);
        $resourceRepository->method('findOneBy')->with(['code' => 'eu'])->willReturn($resource);

        $form = $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            null,
            ['resource' => 'sylius.zone', 'choice_name' => 'name', 'choice_value' => 'code'],
        );

        $form->submit('eu');

        $this->assertEquals('eu', $form->getViewData());
    }

    /**
     * @test
     */
    public function it_has_different_view_based_on_passed_configuration(): void
    {
        /** @var MockObject|RepositoryInterface $resourceRepository */
        $resourceRepository = $this->createMock(RepositoryInterface::class);
        $resource = $this->createMock(ResourceInterface::class);

        $this->resourceRepositoryRegistry->method('get')->with('sylius.zone')->willReturn($resourceRepository);
        $resourceRepository->method('findOneBy')->with(['code' => 'eu'])->willReturn($resource);

        $form = $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            null,
            ['resource' => 'sylius.zone', 'choice_name' => 'name', 'choice_value' => 'code'],
        );

        $formViewVars = $form->createView()->vars;

        $this->assertSame('name', $formViewVars['choice_name']);
        $this->assertSame('code', $formViewVars['choice_value']);
        $this->assertFalse($formViewVars['multiple']);
        $this->assertSame('', $formViewVars['placeholder']);
    }

    /**
     * @test
     */
    public function it_returns_collection_of_resources_from_identifiers(): void
    {
        /** @var MockObject|RepositoryInterface $resourceRepository */
        $resourceRepository = $this->createMock(RepositoryInterface::class);
        $mug = $this->createMock(ResourceInterface::class);
        $book = $this->createMock(ResourceInterface::class);
        $sticker = $this->createMock(ResourceInterface::class);

        $this->resourceRepositoryRegistry->method('get')->with('sylius.resource')->willReturn($resourceRepository);
        $resourceRepository->method('findOneBy')->willReturnCallback(function (array $criteria) use ($mug, $book, $sticker) {
            if ($criteria === ['code' => 'mug']) {
                return $mug;
            }
            if ($criteria === ['code' => 'book']) {
                return $book;
            }
            if ($criteria === ['code' => 'sticker']) {
                return $sticker;
            }

            return null;
        });

        $form = $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 'sylius.resource', 'choice_name' => 'name', 'choice_value' => 'code', 'multiple' => true],
        );

        $form->submit('mug,book,sticker');

        $this->assertEquals(
            new ArrayCollection([$mug, $book, $sticker]),
            $form->getData(),
        );
    }

    /**
     * @test
     */
    public function its_resource_option_should_be_string(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 1, 'choice_name' => 'name', 'choice_value' => 'code', 'multiple' => true],
        );
    }

    /**
     * @test
     */
    public function its_choice_name_option_should_be_string(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 1, 'choice_name' => 1, 'choice_value' => 'code', 'multiple' => true],
        );
    }

    /**
     * @test
     */
    public function its_choice_value_option_should_be_string(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 'sylius.resource', 'choice_name' => 'name', 'choice_value' => 1, 'multiple' => true],
        );
    }

    /**
     * @test
     */
    public function its_multiple_option_should_be_boolean(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 'sylius.resource', 'choice_name' => 'name', 'choice_value' => 'code', 'multiple' => 'yes'],
        );
    }

    /**
     * @test
     */
    public function its_placeholder_option_should_be_string(): void
    {
        $this->expectException(InvalidOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 'sylius.resource', 'choice_name' => 'name', 'choice_value' => 'code', 'placeholder' => 1],
        );
    }

    /**
     * @test
     */
    public function it_cannot_be_created_without_resource_option(): void
    {
        $this->expectException(MissingOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['choice_name' => 'name', 'choice_value' => 'code'],
        );
    }

    /**
     * @test
     */
    public function it_cannot_be_created_without_choice_name_option(): void
    {
        $this->expectException(MissingOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 'sylius.resource', 'choice_value' => 'code'],
        );
    }

    /**
     * @test
     */
    public function it_cannot_be_created_without_choice_value_option(): void
    {
        $this->expectException(MissingOptionsException::class);

        $this->factory->create(
            ResourceAutocompleteChoiceType::class,
            new ArrayCollection(),
            ['resource' => 'sylius.resource', 'choice_name' => 'name'],
        );
    }
}
