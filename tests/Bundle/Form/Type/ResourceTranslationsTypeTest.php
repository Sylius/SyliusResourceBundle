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
use Sylius\Bundle\ResourceBundle\Form\Type\FixedCollectionType;
use Sylius\Bundle\ResourceBundle\Form\Type\ResourceTranslationsType;
use Sylius\Resource\Model\TranslatableInterface;
use Sylius\Resource\Model\TranslationInterface;
use Sylius\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ResourceTranslationsTypeTest extends TestCase
{
    private const DEFAULT_LOCALES = ['en_US', 'pl_PL', 'de_DE'];

    private const DEFAULT_LOCALE = 'en_US';

    /** @var TranslationLocaleProviderInterface&MockObject */
    private TranslationLocaleProviderInterface $localeProvider;

    private ResourceTranslationsType $formType;

    protected function setUp(): void
    {
        $this->localeProvider = $this->createLocaleProvider(self::DEFAULT_LOCALES, self::DEFAULT_LOCALE);
        $this->formType = new ResourceTranslationsType($this->localeProvider);
    }

    public function testExtendsAbstractType(): void
    {
        self::assertInstanceOf(AbstractType::class, $this->formType);
    }

    public function testGetParent(): void
    {
        self::assertSame(FixedCollectionType::class, $this->formType->getParent());
    }

    public function testGetBlockPrefix(): void
    {
        self::assertSame('sylius_translations', $this->formType->getBlockPrefix());
    }

    public function testConfigureOptionsWithDefaults(): void
    {
        $options = $this->resolveOptions();

        self::assertArrayHasKey('entries', $options);
        self::assertArrayHasKey('entry_name', $options);
        self::assertArrayHasKey('entry_options', $options);
        self::assertSame(self::DEFAULT_LOCALES, $options['entries']);
    }

    public function testEntryNameCallableReturnsLocaleCode(): void
    {
        $options = $this->resolveOptions();
        $entryName = $options['entry_name'];

        self::assertIsCallable($entryName);
        self::assertSame('en_US', $entryName('en_US'));
        self::assertSame('pl_PL', $entryName('pl_PL'));
    }

    public function testEntryOptionsCallableReturnsRequiredTrueForDefaultLocale(): void
    {
        $options = $this->resolveOptions();
        $entryOptions = $options['entry_options'];

        self::assertIsCallable($entryOptions);

        $defaultLocaleOptions = $entryOptions(self::DEFAULT_LOCALE);
        self::assertArrayHasKey('required', $defaultLocaleOptions);
        self::assertTrue($defaultLocaleOptions['required']);
    }

    public function testEntryOptionsCallableReturnsRequiredFalseForNonDefaultLocale(): void
    {
        $options = $this->resolveOptions();
        $entryOptions = $options['entry_options'];

        self::assertIsCallable($entryOptions);

        $nonDefaultLocaleOptions = $entryOptions('pl_PL');
        self::assertArrayHasKey('required', $nonDefaultLocaleOptions);
        self::assertFalse($nonDefaultLocaleOptions['required']);
    }

    public function testBuildFormAddsSubmitEventListener(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('addEventListener')
            ->with(
                self::identicalTo('form.submit'),
                self::callback(fn ($callback): bool => is_callable($callback)),
            );

        $this->formType->buildForm($builder, []);
    }

    public function testUsesCustomLocalesFromProvider(): void
    {
        $customLocales = ['fr_FR', 'es_ES'];
        $customDefault = 'fr_FR';
        $formType = new ResourceTranslationsType($this->createLocaleProvider($customLocales, $customDefault));

        $options = $this->resolveOptions($formType);
        $entryOptions = $options['entry_options'];

        self::assertSame($customLocales, $options['entries']);
        self::assertIsCallable($entryOptions);

        $defaultLocaleOptions = $entryOptions($customDefault);
        self::assertTrue($defaultLocaleOptions['required']);

        $nonDefaultLocaleOptions = $entryOptions('es_ES');
        self::assertFalse($nonDefaultLocaleOptions['required']);
    }

    public function testEventListenerSetsLocaleAndTranslatableOnTranslations(): void
    {
        $translatable = $this->createMock(TranslatableInterface::class);

        $translation1 = $this->createTranslationMock('en_US', $translatable);
        $translation2 = $this->createTranslationMock('pl_PL', $translatable);

        $translations = [
            'en_US' => $translation1,
            'pl_PL' => $translation2,
        ];

        $listener = $this->captureEventListener();
        $event = $this->createFormEvent($translations, $translatable);

        $listener($event);

        self::assertSame($translations, $event->getData());
    }

    public function testEventListenerRemovesNullTranslations(): void
    {
        $translatable = $this->createMock(TranslatableInterface::class);
        $translation = $this->createTranslationMock('en_US', $translatable);

        $translations = [
            'en_US' => $translation,
            'pl_PL' => null,
            'de_DE' => null,
        ];

        $listener = $this->captureEventListener();
        $event = $this->createFormEvent($translations, $translatable);

        $listener($event);

        $result = $event->getData();
        self::assertIsArray($result);
        self::assertArrayHasKey('en_US', $result);
        self::assertArrayNotHasKey('pl_PL', $result);
        self::assertArrayNotHasKey('de_DE', $result);
        self::assertCount(1, $result);
    }

    public function testEventListenerHandlesEmptyTranslations(): void
    {
        $translatable = $this->createMock(TranslatableInterface::class);

        $listener = $this->captureEventListener();
        $event = $this->createFormEvent([], $translatable);

        $listener($event);

        self::assertSame([], $event->getData());
    }

    public function testEventListenerHandlesAllNullTranslations(): void
    {
        $translatable = $this->createMock(TranslatableInterface::class);
        $translations = [
            'en_US' => null,
            'pl_PL' => null,
        ];

        $listener = $this->captureEventListener();
        $event = $this->createFormEvent($translations, $translatable);

        $listener($event);

        self::assertSame([], $event->getData());
    }

    /**
     * @param string[] $locales
     *
     * @return TranslationLocaleProviderInterface&MockObject
     */
    private function createLocaleProvider(array $locales, string $defaultLocale): TranslationLocaleProviderInterface
    {
        $provider = $this->createMock(TranslationLocaleProviderInterface::class);
        $provider->method('getDefinedLocalesCodes')->willReturn($locales);
        $provider->method('getDefaultLocaleCode')->willReturn($defaultLocale);

        return $provider;
    }

    /** @return array<string, mixed> */
    private function resolveOptions(?ResourceTranslationsType $formType = null): array
    {
        $resolver = new OptionsResolver();
        ($formType ?? $this->formType)->configureOptions($resolver);

        return $resolver->resolve();
    }

    /** @return TranslationInterface&MockObject */
    private function createTranslationMock(string $locale, TranslatableInterface $translatable): TranslationInterface
    {
        $translation = $this->createMock(TranslationInterface::class);
        $translation->expects(self::once())->method('setLocale')->with($locale);
        $translation->expects(self::once())->method('setTranslatable')->with($translatable);

        return $translation;
    }

    private function captureEventListener(): callable
    {
        $listener = null;

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('addEventListener')
            ->willReturnCallback(function ($eventName, $callback) use (&$listener, $builder) {
                $listener = $callback;

                return $builder;
            });

        $this->formType->buildForm($builder, []);

        return $listener;
    }

    /**
     * @param array<string, TranslationInterface|null> $translations
     */
    private function createFormEvent(array $translations, TranslatableInterface $translatable): FormEvent
    {
        $parentForm = $this->createMock(FormInterface::class);
        $parentForm->method('getData')->willReturn($translatable);

        $form = $this->createMock(FormInterface::class);
        $form->method('getParent')->willReturn($parentForm);

        /** @var array<string, TranslationInterface|null>|null $updatedData */
        $updatedData = null;

        $event = $this->createMock(FormEvent::class);
        $event
            ->method('getData')
            ->willReturnCallback(function () use (&$updatedData, $translations) {
                return null !== $updatedData ? $updatedData : $translations;
            });
        $event
            ->method('getForm')
            ->willReturn($form);
        $event
            ->expects(self::once())
            ->method('setData')
            ->willReturnCallback(function ($data) use (&$updatedData): void {
                $updatedData = $data;
            });

        return $event;
    }
}
