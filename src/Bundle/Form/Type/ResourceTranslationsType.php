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

use Sylius\Component\Resource\Model\TranslatableInterface;
use Sylius\Component\Resource\Model\TranslationInterface;
use Sylius\Component\Resource\Translation\Provider\TranslationLocaleProviderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class ResourceTranslationsType extends AbstractType
{
    /** @var string[] */
    private array $definedLocalesCodes;

    private string $defaultLocaleCode;

    public function __construct(TranslationLocaleProviderInterface $localeProvider)
    {
        $this->definedLocalesCodes = $localeProvider->getDefinedLocalesCodes();
        $this->defaultLocaleCode = $localeProvider->getDefaultLocaleCode();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            /** @var TranslationInterface[]|null[] $translations */
            $translations = $event->getData();

            $parentForm = $event->getForm()->getParent();
            Assert::notNull($parentForm);

            /** @var TranslatableInterface $translatable */
            $translatable = $parentForm->getData();

            foreach ($translations as $localeCode => $translation) {
                if (null === $translation) {
                    unset($translations[$localeCode]);

                    continue;
                }

                $translation->setLocale($localeCode);
                $translation->setTranslatable($translatable);
            }

            $event->setData($translations);
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'entries' => $this->definedLocalesCodes,
            'entry_name' => function (string $localeCode): string {
                return $localeCode;
            },
            'entry_options' => function (string $localeCode): array {
                return [
                    'required' => $localeCode === $this->defaultLocaleCode,
                ];
            },
        ]);
    }

    public function getParent(): string
    {
        return FixedCollectionType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_translations';
    }
}
