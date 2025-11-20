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

namespace Sylius\Resource\Tests\Symfony\Session\Flash;

use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;
use Sylius\Resource\Symfony\Session\Flash\FlashHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashHelperTest extends TestCase
{
    private TranslatorInterface $translator;

    private FlashHelper $flashHelper;

    protected function setUp(): void
    {
        $this->translator = $this->createMock(TranslatorInterface::class);
        $this->flashHelper = new FlashHelper($this->translator);
    }

    public function testItIsInitializable(): void
    {
        $this->assertInstanceOf(FlashHelper::class, $this->flashHelper);
    }

    public function testItAddsSuccessFlashesWithCustomMessage(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $flashBag->expects($this->once())->method('add')->with('success', 'Custom message.');

        $this->flashHelper->addSuccessFlash($operation, $context, 'Custom message.');
    }

    public function testItAddsSuccessFlashesWithSpecificMessage(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.dummy.create', 'flashes')->willReturn(true);
        $translator->expects($this->once())->method('trans')->with('app.dummy.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Dummy was created successfully.');

        $flashHelper->addSuccessFlash($operation, $context);
    }

    public function testItAddsSuccessFlashesWithFallbackMessage(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.dummy.create', 'flashes')->willReturn(false);
        $translator->expects($this->once())->method('trans')->with('sylius.resource.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Dummy was created successfully.');

        $flashHelper->addSuccessFlash($operation, $context);
    }

    public function testItAddsSuccessFlashesWithCustomMessageOnItsOperation(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create(notificationMessage: 'app.dummy.shipped'))->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.dummy.shipped', 'flashes')->willReturn(true);
        $translator->expects($this->once())->method('trans')->with('app.dummy.shipped', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was shipped successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Dummy was shipped successfully.');

        $flashHelper->addSuccessFlash($operation, $context);
    }

    public function testItAddsSuccessFlashesWithDefaultMessageWhenTranslatorIsNotABag(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $this->translator->expects($this->once())->method('trans')->with('sylius.resource.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Dummy was created successfully.');

        $this->flashHelper->addSuccessFlash($operation, $context);
    }

    public function testItAddsSuccessFlashesWithHumanizedMessage(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'admin_user', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.admin_user.create', 'flashes')->willReturn(true);
        $translator->expects($this->once())->method('trans')->with('app.admin_user.create', ['%resource%' => 'Admin user'], 'flashes')->willReturn('Admin user was created successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Admin user was created successfully.');

        $flashHelper->addSuccessFlash($operation, $context);
    }

    public function testItAddsSuccessFlashesWithHumanizedMessageAndPluralNameOnBulkOperation(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $flashHelper = new FlashHelper($translator);

        $operation = (new BulkDelete())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'admin_user', pluralName: 'admin_users', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.admin_user.bulk_delete', 'flashes')->willReturn(true);
        $translator->expects($this->once())->method('trans')->with('app.admin_user.bulk_delete', ['%resources%' => 'Admin users'], 'flashes')->willReturn('Admin users was removed successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Admin users was removed successfully.');

        $flashHelper->addSuccessFlash($operation, $context);
    }

    public function testItAddsErrorFlashesWithCustomMessage(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $flashBag->expects($this->once())->method('add')->with('error', 'Custom error message.');

        $this->flashHelper->addErrorFlash($operation, $context, 'Custom error message.');
    }

    public function testItAddsErrorFlashesWithSpecificMessage(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.dummy.create_error', 'flashes')->willReturn(true);
        $translator->expects($this->once())->method('trans')->with('app.dummy.create_error', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Cannot create Dummy resource.');

        $flashBag->expects($this->once())->method('add')->with('error', 'Cannot create Dummy resource.');

        $flashHelper->addErrorFlash($operation, $context);
    }

    public function testItAddsErrorFlashesWithFallbackMessage(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.dummy.create_error', 'flashes')->willReturn(false);
        $translator->expects($this->once())->method('trans')->with('sylius.resource.create_error', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Cannot create Dummy resource.');

        $flashBag->expects($this->once())->method('add')->with('error', 'Cannot create Dummy resource.');

        $flashHelper->addErrorFlash($operation, $context);
    }

    public function testItTranslatesFlashesFromEventWhenTranslatorIsNotABag(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $event = $this->createMock(GenericEvent::class);

        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $event->method('getMessage')->willReturn('app.admin_user.banned');
        $event->method('getMessageType')->willReturn('success');
        $event->method('getMessageParameters')->willReturn(['%admin_user%' => 'Darth Vader']);

        $this->translator->expects($this->once())->method('trans')->with('app.admin_user.banned', ['%admin_user%' => 'Darth Vader'], 'flashes')->willReturn('Darth Vader was banned successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Darth Vader was banned successfully.');

        $this->flashHelper->addFlashFromEvent($event, $context);
    }

    public function testItTranslatesFlashesFromEventWhenTranslatorIsABag(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);
        $event = $this->createMock(GenericEvent::class);

        $flashHelper = new FlashHelper($translator);

        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $event->method('getMessage')->willReturn('app.admin_user.banned');
        $event->method('getMessageType')->willReturn('success');
        $event->method('getMessageParameters')->willReturn(['%admin_user%' => 'Darth Vader']);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('app.admin_user.banned', 'flashes')->willReturn(true);
        $translator->expects($this->once())->method('trans')->with('app.admin_user.banned', ['%admin_user%' => 'Darth Vader'], 'flashes')->willReturn('Darth Vader was banned successfully.');

        $flashBag->expects($this->once())->method('add')->with('success', 'Darth Vader was banned successfully.');

        $flashHelper->addFlashFromEvent($event, $context);
    }

    public function testItDoesNotTranslateEventMessageWhenTranslatorIsABagAndDoesNotContainsTheKey(): void
    {
        $request = $this->createMock(Request::class);
        $session = $this->createMock(SessionInterface::class);
        $flashBag = $this->createMock(FlashBagInterface::class);
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);
        $event = $this->createMock(GenericEvent::class);

        $flashHelper = new FlashHelper($translator);

        $context = new Context(new RequestOption($request));

        $request->method('getSession')->willReturn($session);
        $session->method('getBag')->with('flashes')->willReturn($flashBag);

        $event->method('getMessage')->willReturn('Darth Vader was banned successfully.');
        $event->method('getMessageType')->willReturn('success');
        $event->method('getMessageParameters')->willReturn([]);

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('Darth Vader was banned successfully.', 'flashes')->willReturn(false);

        $translator->expects($this->never())->method('trans');

        $flashBag->expects($this->once())->method('add')->with('success', 'Darth Vader was banned successfully.');

        $flashHelper->addFlashFromEvent($event, $context);
    }
}
