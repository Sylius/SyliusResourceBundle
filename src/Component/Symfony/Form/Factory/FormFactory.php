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

namespace Sylius\Component\Resource\Symfony\Form\Factory;

use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Operation;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactoryInterface;
use Symfony\Component\Form\FormInterface;

final class FormFactory implements FormFactoryInterface
{
    private SymfonyFormFactoryInterface $formFactory;

    public function __construct(SymfonyFormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    public function create(Operation $operation, Context $context, mixed $data = null): FormInterface
    {
        $formType = $operation->getFormType();
        $formOptions = $operation->getFormOptions() ?? [];

        if (null === $formType) {
            throw new \RuntimeException(sprintf('Operation "%s" has no configured form type.', $operation->getName() ?? ''));
        }

        $request = $context->get(RequestOption::class)?->request();

        if ('html' === $request?->getRequestFormat()) {
            return $this->formFactory->create($formType, $data, $formOptions);
        }

        return $this->formFactory->createNamed('', $formType, $data, array_merge($formOptions, ['csrf_protection' => false]));
    }
}
