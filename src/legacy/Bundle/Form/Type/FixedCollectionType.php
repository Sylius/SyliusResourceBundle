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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Webmozart\Assert\Assert;

final class FixedCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        Assert::isIterable($options['entries']);

        foreach ($options['entries'] as $entry) {
            Assert::isCallable($options['entry_type']);
            Assert::isCallable($options['entry_name']);
            Assert::isCallable($options['entry_options']);

            $entryType = $options['entry_type']($entry);
            $entryName = $options['entry_name']($entry);
            $entryOptions = $options['entry_options']($entry);

            $builder->add($entryName, $entryType, array_replace([
                'property_path' => '[' . $entryName . ']',
                'block_name' => 'entry',
            ], $entryOptions));
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('entries');
        $resolver->setAllowedTypes('entries', ['array', \Traversable::class]);

        $resolver->setRequired('entry_type');
        $resolver->setAllowedTypes('entry_type', ['string', 'callable']);
        $resolver->setNormalizer('entry_type', $this->optionalCallableNormalizer());

        $resolver->setRequired('entry_name');
        $resolver->setAllowedTypes('entry_name', ['callable']);

        $resolver->setDefault('entry_options', function () {
            return [];
        });
        $resolver->setAllowedTypes('entry_options', ['array', 'callable']);
        $resolver->setNormalizer('entry_options', $this->optionalCallableNormalizer());
    }

    public function getBlockPrefix(): string
    {
        return 'sylius_fixed_collection';
    }

    private function optionalCallableNormalizer(): \Closure
    {
        return
            /**
             * @param mixed $value
             *
             * @return mixed
             */
            function (Options $options, $value) {
                if (is_callable($value)) {
                    return $value;
                }

                return /** @return mixed */ function () use ($value) {
                    return $value;
                };
            }
        ;
    }
}
