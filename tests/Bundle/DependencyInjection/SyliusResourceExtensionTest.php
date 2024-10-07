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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection;

use App\Entity\Book;
use App\Entity\BookTranslation;
use App\Entity\ComicBook;
use App\Factory\BookFactory;
use App\Form\Type\BookType;
use App\Repository\ComicBookRepository;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\DependencyInjection\SyliusResourceExtension;
use Sylius\Bundle\ResourceBundle\Form\Type\DefaultResourceType;
use Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Dummy\BookWithAliasResource;
use Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Dummy\DummyResource;
use Sylius\Bundle\ResourceBundle\Tests\DependencyInjection\Dummy\NoDriverResource;
use Sylius\Resource\Factory\Factory;

class SyliusResourceExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_registers_services_and_parameters_for_resources(): void
    {
        // TODO: Move Resource-Grid integration to a dedicated compiler pass
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'resources' => [
                'app.book' => [
                    'classes' => [
                        'model' => Book::class,
                        'form' => BookType::class,
                    ],
                    'translation' => [
                        'classes' => [
                            'model' => BookTranslation::class,
                         ],
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasService('app.factory.book');
        $this->assertContainerBuilderHasService('app.repository.book');
        $this->assertContainerBuilderHasService('app.controller.book');
        $this->assertContainerBuilderHasService('app.manager.book');
        $this->assertContainerBuilderNotHasService(ResourceController::class);

        $this->assertContainerBuilderHasParameter('app.model.book.class', Book::class);
        $this->assertContainerBuilderHasParameter('app.model.book_translation.class', BookTranslation::class);

        $this->assertContainerBuilderHasParameter('app.form.book.class', BookType::class);
    }

    /**
     * @test
     */
    public function it_registers_the_entity_repository_classes_from_entity_attributes(): void
    {
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'resources' => [
                'app.comic_book' => [
                    'classes' => [
                        'model' => ComicBook::class,
                    ],
                ],
            ],
        ]);

        $this->assertContainerBuilderHasService('app.repository.comic_book', ComicBookRepository::class);
    }

    /**
     * @test
     */
    public function it_aliases_authorization_checker_with_the_one_given_in_configuration(): void
    {
        // TODO: Move Resource-Grid integration to a dedicated compiler pass
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'authorization_checker' => 'custom_service',
        ]);

        $this->assertContainerBuilderHasAlias('sylius.resource_controller.authorization_checker', 'custom_service');
    }

    /**
     * @test
     */
    public function it_registers_default_translation_parameters(): void
    {
        // TODO: Move ResourceGrid integration to a dedicated compiler pass
        $this->setParameter('kernel.bundles', []);

        $this->load([
             'translation' => [
                 'locale_provider' => 'test.custom_locale_provider',
             ],
         ]);

        $this->assertContainerBuilderHasAlias('sylius.translation_locale_provider', 'test.custom_locale_provider');
    }

    /**
     * @test
     */
    public function it_does_not_break_when_aliasing_two_resources_use_same_factory_class(): void
    {
        // TODO: Move Resource-Grid integration to a dedicated compiler pass
        $this->setParameter('kernel.bundles', []);
        $this->load([
            'resources' => [
                'app.book' => [
                    'classes' => [
                        'model' => Book::class,
                        'factory' => BookFactory::class,
                    ],
                ],
                'app.comic_book' => [
                    'classes' => [
                        'model' => ComicBook::class,
                        'factory' => BookFactory::class,
                    ],
                ],
            ],
        ]);
        $this->assertContainerBuilderHasService('app.factory.book');
        $this->assertContainerBuilderHasService('app.factory.comic_book');

        $this->assertContainerBuilderHasAlias(sprintf('%s $bookFactory', BookFactory::class), 'app.factory.book');
        $this->assertContainerBuilderHasAlias(sprintf('%s $comicBookFactory', BookFactory::class), 'app.factory.comic_book');
    }

    /**
     * @test
     */
    public function it_registers_parameter_for_paths(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->load([
            'mapping' => [
                'paths' => [
                    __DIR__ . '/Dummy',
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.mapping', [
            'paths' => [
                __DIR__ . '/Dummy',
            ],
        ]);
    }

    /**
     * @test
     */
    public function it_auto_registers_resources(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->load([
            'mapping' => [
                'paths' => [
                    __DIR__ . '/Dummy',
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resources', [
            'app.book' => [
                'classes' => [
                    'model' => BookWithAliasResource::class,
                    'controller' => ResourceController::class,
                    'factory' => Factory::class,
                    'form' => DefaultResourceType::class,
                ],
                'driver' => 'doctrine/orm',
            ],
            'app.dummy' => [
                'classes' => [
                    'model' => DummyResource::class,
                    'controller' => ResourceController::class,
                    'factory' => Factory::class,
                    'form' => DefaultResourceType::class,
                ],
                'driver' => 'doctrine/orm',
            ],
            'app.no_driver' => [
                'classes' => [
                    'model' => NoDriverResource::class,
                    'controller' => ResourceController::class,
                    'factory' => Factory::class,
                    'form' => DefaultResourceType::class,
                ],
                'driver' => false,
            ],
        ]);
    }

    protected function getContainerExtensions(): array
    {
        return [
            new SyliusResourceExtension(),
        ];
    }
}
