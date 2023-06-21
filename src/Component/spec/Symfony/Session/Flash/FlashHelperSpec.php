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
use Prophecy\Argument;
use Sylius\Component\Resource\Context\Context;
use Sylius\Component\Resource\Context\Option\RequestOption;
use Sylius\Component\Resource\Metadata\BulkDelete;
use Sylius\Component\Resource\Metadata\Create;
use Sylius\Component\Resource\Metadata\Resource;
use Sylius\Component\Resource\Symfony\EventDispatcher\GenericEvent;
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
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('app.dummy.create', 'flashes')->willReturn(true)->shouldBeCalled();

        $translator->trans('app.dummy.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Dummy was created successfully.')->shouldBeCalled();

        $this->addSuccessFlash($operation, $context);
    }

    function it_adds_success_flashes_with_fallback_message(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorBagInterface $translator,
        MessageCatalogueInterface $messageCatalogue,
    ): void {
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
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
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
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
        $operation = (new Create())->withResource(new Resource(alias: 'app.dummy', name: 'admin_user', applicationName: 'app'));
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('app.admin_user.create', 'flashes')->willReturn(true)->shouldBeCalled();

        $translator->trans('app.admin_user.create', ['%resource%' => 'Admin user'], 'flashes')->willReturn('Admin user was created successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Admin user was created successfully.')->shouldBeCalled();

        $this->addSuccessFlash($operation, $context);
    }

    function it_adds_success_flashes_with_humanized_message_and_plural_name_on_bulk_operation(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorBagInterface $translator,
        MessageCatalogueInterface $messageCatalogue,
    ): void {
        $operation = (new BulkDelete())->withResource(new Resource(alias: 'app.dummy', name: 'admin_user', pluralName: 'admin_users', applicationName: 'app'));
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('app.admin_user.bulk_delete', 'flashes')->willReturn(true)->shouldBeCalled();

        $translator->trans('app.admin_user.bulk_delete', ['%resources%' => 'Admin users'], 'flashes')->willReturn('Admin users was removed successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Admin users was removed successfully.')->shouldBeCalled();

        $this->addSuccessFlash($operation, $context);
    }

    function it_translates_flashes_from_event_when_translator_is_not_a_bag(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorInterface $translator,
        GenericEvent $event,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $event->getMessage()->willReturn('app.admin_user.banned');
        $event->getMessageType()->willReturn('success');
        $event->getMessageParameters()->willReturn(['%admin_user%' => 'Darth Vader']);

        $translator->trans('app.admin_user.banned', ['%admin_user%' => 'Darth Vader'], 'flashes')->willReturn('Darth Vader was banned successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Darth Vader was banned successfully.')->shouldBeCalled();

        $this->addFlashFromEvent($event, $context);
    }

    function it_translates_flashes_from_event_when_translator_is_a_bag(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorBagInterface $translator,
        MessageCatalogueInterface $messageCatalogue,
        GenericEvent $event,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $event->getMessage()->willReturn('app.admin_user.banned');
        $event->getMessageType()->willReturn('success');
        $event->getMessageParameters()->willReturn(['%admin_user%' => 'Darth Vader']);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('app.admin_user.banned', 'flashes')->willReturn(true)->shouldBeCalled();

        $translator->trans('app.admin_user.banned', ['%admin_user%' => 'Darth Vader'], 'flashes')->willReturn('Darth Vader was banned successfully.')->shouldBeCalled();

        $flashBag->add('success', 'Darth Vader was banned successfully.')->shouldBeCalled();

        $this->addFlashFromEvent($event, $context);
    }

    function it_does_not_translate_event_message_when_translator_is_a_bag_and_does_not_contains_the_key(
        Request $request,
        SessionInterface $session,
        FlashBagInterface $flashBag,
        TranslatorBagInterface $translator,
        MessageCatalogueInterface $messageCatalogue,
        GenericEvent $event,
    ): void {
        $context = new Context(new RequestOption($request->getWrappedObject()));

        $request->getSession()->willReturn($session);

        $session->getBag('flashes')->willReturn($flashBag);

        $event->getMessage()->willReturn('Darth Vader was banned successfully.');
        $event->getMessageType()->willReturn('success');
        $event->getMessageParameters()->willReturn([]);

        $translator->getCatalogue()->willReturn($messageCatalogue);

        $messageCatalogue->has('Darth Vader was banned successfully.', 'flashes')->willReturn(false)->shouldBeCalled();

        $translator->trans(Argument::cetera())->shouldNotBeCalled();

        $flashBag->add('success', 'Darth Vader was banned successfully.')->shouldBeCalled();

        $this->addFlashFromEvent($event, $context);
    }
}
