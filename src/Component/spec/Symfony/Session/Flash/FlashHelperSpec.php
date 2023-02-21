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

namespace spec\Sylius\Component\Resource\Symfony\Session\Flash;

use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\Session\Flash\FlashHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashHelperSpec extends ObjectBehavior
{
    function let(TranslatorInterface $translator): void
    {
        $this->beConstructedWith($translator);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(FlashHelper::class);
    }

    function it_adds_success_flashes_with_specific_message(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorBagInterface $translator,
        MessageCatalogueInterface $messageCatalogue,
    ): void {
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', applicationName: 'app', name: 'dummy'));
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('app.dummy.create', 'flashes')->willReturn(true)->shouldBeCalled();

        $translator->trans('app.dummy.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Dummy was created successfully.')->shouldBeCalled();

        $this->addSuccessFlash($operation, $context);
    }

    function it_adds_success_flashes_with_default_message(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorBagInterface $translator,
        MessageCatalogueInterface $messageCatalogue,
    ): void {
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', applicationName: 'app', name: 'dummy'));
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('app.dummy.create', 'flashes')->willReturn(false)->shouldBeCalled();

        $translator->trans('sylius.resource.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Dummy was created successfully.')->shouldBeCalled();

        $this->addSuccessFlash($operation, $context);
    }

    function it_adds_success_flashes_with_default_message_when_translator_is_not_a_bag(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorInterface $translator,
    ): void {
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', applicationName: 'app', name: 'dummy'));
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $translator->trans('sylius.resource.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Dummy was created successfully.')->shouldBeCalled();

        $this->addSuccessFlash($operation, $context);
    }

    function it_adds_success_flashes_with_humanized_message(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorBagInterface $translator,
        MessageCatalogueInterface $messageCatalogue,
    ): void {
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', applicationName: 'app', name: 'admin_user'));
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('app.admin_user.create', 'flashes')->willReturn(true)->shouldBeCalled();

        $translator->trans('app.admin_user.create', ['%resource%' => 'Admin user'], 'flashes')->willReturn('Dummy was created successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Dummy was created successfully.')->shouldBeCalled();

        $this->addSuccessFlash($operation, $context);
    }
}
