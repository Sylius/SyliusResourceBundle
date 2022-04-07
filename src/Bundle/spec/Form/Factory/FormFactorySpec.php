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

namespace spec\Sylius\Bundle\ResourceBundle\Form\Factory;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Form\Factory\FormFactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Symfony\Component\Form\FormFactoryInterface as SymfonyFormFactoryInterface;
use Symfony\Component\Form\FormInterface;

final class FormFactorySpec extends ObjectBehavior
{
    function let(SymfonyFormFactoryInterface $formFactory): void
    {
        $this->beConstructedWith($formFactory);
    }

    function it_implements_resource_form_factory_interface(): void
    {
        $this->shouldImplement(FormFactoryInterface::class);
    }

    function it_creates_appropriate_form_based_on_configuration(
        RequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
        SymfonyFormFactoryInterface $formFactory,
        FormInterface $form
    ): void {
        $requestConfiguration->isHtmlRequest()->willReturn(true);
        $requestConfiguration->getFormType()->willReturn('sylius_product_pricing');
        $requestConfiguration->getFormOptions()->willReturn([]);
        $formFactory->create('sylius_product_pricing', $resource, Argument::type('array'))->willReturn($form);

        $this->create($requestConfiguration, $resource)->shouldReturn($form);
    }

    function it_creates_form_without_passing_any_data(
        RequestConfiguration $requestConfiguration,
        SymfonyFormFactoryInterface $formFactory,
        FormInterface $form
    ): void {
        $requestConfiguration->isHtmlRequest()->willReturn(true);
        $requestConfiguration->getFormType()->willReturn('sylius_register');
        $requestConfiguration->getFormOptions()->willReturn([]);
        $formFactory->create('sylius_register', null, Argument::type('array'))->willReturn($form);

        $this->create($requestConfiguration, null)->shouldReturn($form);
    }

    function it_creates_form_without_root_name_and_disables_csrf_protection_for_non_html_requests(
        RequestConfiguration $requestConfiguration,
        ResourceInterface $resource,
        SymfonyFormFactoryInterface $formFactory,
        FormInterface $form
    ): void {
        $requestConfiguration->isHtmlRequest()->willReturn(false);
        $requestConfiguration->getFormType()->willReturn('sylius_product_api');
        $requestConfiguration->getFormOptions()->willReturn([]);
        $formFactory->createNamed('', 'sylius_product_api', $resource, ['csrf_protection' => false])->willReturn($form);

        $this->create($requestConfiguration, $resource)->shouldReturn($form);
    }
}
