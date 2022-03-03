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

namespace Sylius\Bundle\ResourceBundle\Tests\DependencyInjection;

use App\Entity\Book;
use App\Entity\BookTranslation;
use App\Entity\ComicBook;
use App\Factory\BookFactory;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Bundle\ResourceBundle\DependencyInjection\SyliusResourceExtension;

class SyliusResourceExtensionTest extends AbstractExtensionTestCase
{
    /**
     * @test
     */
    public function it_registers_services_and_parameters_for_resources()
    {
        // TODO: Move Resource-Grid integration to a dedicated compiler pass
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'resources' => [
                'app.book' => [
                    'classes' => [
                        'model' => Book::class,
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
    }

    /**
     * @test
     */
    public function it_aliases_authorization_checker_with_the_one_given_in_configuration()
    {
        // TODO: Move Resource-Grid integration to a dedicated compiler pass
        $this->setParameter('kernel.bundles', []);

        $this->load(['authorization_checker' => 'custom_service']);

        $this->assertContainerBuilderHasAlias('sylius.resource_controller.authorization_checker', 'custom_service');
    }

    /**
     * @test
     */
    public function it_registers_translation_enabled_locales_parameter_from_locale_parameter(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->setParameter('locale', 'pl');

        $this->load([
            'translation' => [
                'enabled_locales' => [],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.enabled_locales', ['pl']);
    }

    /**
     * @test
     */
    public function it_registers_translation_enabled_locales_parameter_from_kernel_enabled_locales_parameter(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->setParameter('kernel.enabled_locales', ['fr', 'pl']);

        $this->load([
            'translation' => [
                'enabled_locales' => [],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.enabled_locales', ['fr', 'pl']);
    }

    /**
     * @test
     */
    public function it_registers_translation_enabled_locales_parameter_with_given_locales(): void
    {
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'translation' => [
                'enabled_locales' => ['fr', 'pl'],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.enabled_locales', ['fr', 'pl']);
    }

    /**
     * @test
     */
    public function it_registers_translation_enabled_locales_parameter(): void
    {
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'translation' => [
                'enabled_locales' => [],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.enabled_locales', ['en']);
    }

    /**
     * @test
     */
    public function it_registers_default_locale_parameter_from_locale_parameter(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->setParameter('locale', 'pl');

        $this->load([
            'translation' => [
                'default_locale' => null,
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.default_locale', 'pl');
    }

    /**
     * @test
     */
    public function it_registers_default_locale_parameter_from_kernel_default_locale_parameter(): void
    {
        $this->setParameter('kernel.bundles', []);
        $this->setParameter('kernel.default_locale', 'pl');

        $this->load([
            'translation' => [
                'default_locale' => null,
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.default_locale', 'pl');
    }

    /**
     * @test
     */
    public function it_registers_translation_default_locale_parameter_with_given_locale(): void
    {
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'translation' => [
                'default_locale' => 'fr',
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.default_locale', 'fr');
    }

    /**
     * @test
     */
    public function it_registers_translation_default_locale_parameter(): void
    {
        $this->setParameter('kernel.bundles', []);

        $this->load([
            'translation' => [
                'default_locale' => null,
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.translation.default_locale', 'en');
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
                    'path/to/resources',
                ],
            ],
        ]);

        $this->assertContainerBuilderHasParameter('sylius.resource.mapping', [
            'paths' => [
                'path/to/resources',
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
