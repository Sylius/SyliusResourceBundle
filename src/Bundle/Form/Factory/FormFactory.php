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

namespace Sylius\Bundle\ResourceBundle\Form\Factory;

use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactoryInterface;
use Symfony\Component\Form\FormInterface;

final class FormFactory implements FormFactoryInterface
{
    private SymfonyFormFactoryInterface $formFactory;

    public function __construct(SymfonyFormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function create(RequestConfiguration $requestConfiguration, ?object $data): FormInterface
    {
        $formType = (string) $requestConfiguration->getFormType();
        $formOptions = $requestConfiguration->getFormOptions();

        if ($requestConfiguration->isHtmlRequest()) {
            return $this->formFactory->create($formType, $data, $formOptions);
        }

        return $this->formFactory->createNamed('', $formType, $data, array_merge($formOptions, ['csrf_protection' => false]));
    }
}
