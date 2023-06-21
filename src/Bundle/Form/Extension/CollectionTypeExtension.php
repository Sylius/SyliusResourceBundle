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

namespace Sylius\Bundle\ResourceBundle\Form\Extension;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class CollectionTypeExtension extends AbstractTypeExtension
{
    /**
     * @psalm-suppress MissingPropertyType
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['button_add_label'] = $options['button_add_label'];
        $view->vars['button_delete_label'] = $options['button_delete_label'];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'button_add_label' => 'sylius.form.collection.add',
            'button_delete_label' => 'sylius.form.collection.delete',
        ]);
    }

    public function getExtendedType(): string
    {
        return CollectionType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [CollectionType::class];
    }
}
