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

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Sylius\Resource\Context\Context;
use Sylius\Resource\Context\Option\RequestOption;
use Sylius\Resource\Metadata\BulkDelete;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Symfony\EventDispatcher\GenericEvent;
use Sylius\Resource\Symfony\Session\Flash\FlashHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

#[CoversClass(FlashHelper::class)]
final class FlashHelperTest extends TestCase
{
    public function testItAddsSuccessFlashesWithCustomMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper = new FlashHelper($this->createTranslator());
        $flashHelper->addSuccessFlash($operation, $context, 'Custom message.');

        $this->assertSame('Custom message.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithSpecificMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.dummy.create' => '%resource% was created successfully!!',
            'sylius.resource.create' => '%resource% was created successfully.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Dummy was created successfully!!', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithFallbackMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'sylius.resource.create' => '%resource% was created successfully.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Dummy was created successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithSpecificMessageForCustomOperationName(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.dummy.create_for_customer' => '%resource% has been added to this customer successfully.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create(shortName: 'create_for_customer'))->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Dummy has been added to this customer successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithCustomMessageOnItsOperation(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.dummy.shipped' => '%resource% was shipped successfully.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create(notificationMessage: 'app.dummy.shipped'))->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Dummy was shipped successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithCustomMessageOnItsOperationEvenIfThisIsNotInTheTranslationBag(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator();

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create(notificationMessage: 'Dummy was shipped successfully.'))->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Dummy was shipped successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithFirstDefaultMessageWhenTranslatorIsNotABag(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $translator = $this->createMock(TranslatorInterface::class);
        $flashHelper = new FlashHelper($translator);

        $translator->expects($this->once())->method('trans')->with('app.dummy.create', ['%resource%' => 'Dummy'], 'flashes')->willReturn('Dummy was created successfully.');

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Dummy was created successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithHumanizedMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.admin_user.create' => '%resource% was created successfully.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'admin_user', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Admin user was created successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithTranslatedResourceInTheMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'sylius.resource.create' => '%resource% was created successfully.',
        ], [
            'app.ui.promotion' => 'Cart promotion',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.promotion', name: 'promotion', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Cart promotion was created successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithHumanizedMessageAndPluralNameOnBulkOperation(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.admin_user.bulk_delete' => '%resources% was removed successfully.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new BulkDelete())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'admin_user', pluralName: 'admin_users', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Admin users was removed successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsSuccessFlashesWithTranslatedResourceWithPluralNameInTheMessageOnBulkOperation(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.promotion.bulk_delete' => '%resources% was removed successfully.',
        ], [
            'app.ui.promotions' => 'Cart promotions',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new BulkDelete())->withResource(new ResourceMetadata(alias: 'app.promotion', name: 'promotion', pluralName: 'promotions', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addSuccessFlash($operation, $context);

        $this->assertSame('Cart promotions was removed successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItAddsErrorFlashesWithCustomMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper = new FlashHelper($this->createTranslator());

        $flashHelper->addErrorFlash($operation, $context, 'Custom error message.');

        $this->assertSame('Custom error message.', $session->getFlashBag()->all()['error'][0]);
    }

    public function testItAddsErrorFlashesWithSpecificMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.dummy.create_error' => 'Cannot create %resource% resource!!',
            'sylius.resource.create_error' => 'Cannot create %resource% resource.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addErrorFlash($operation, $context);

        $this->assertSame('Cannot create Dummy resource!!', $session->getFlashBag()->all()['error'][0]);
    }

    public function testItAddsErrorFlashesWithFallbackMessage(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'sylius.resource.create_error' => 'Cannot create %resource% resource.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addErrorFlash($operation, $context);

        $this->assertSame('Cannot create Dummy resource.', $session->getFlashBag()->all()['error'][0]);
    }

    public function testItAddsErrorFlashesWithSpecificMessageForCustomOperationName(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'app.dummy.create_for_customer_error' => 'Cannot create %resource% resource for this customer.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create(shortName: 'create_for_customer'))->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addErrorFlash($operation, $context);

        $this->assertSame('Cannot create Dummy resource for this customer.', $session->getFlashBag()->all()['error'][0]);
    }

    public function testItAddsErrorFlashesWithFallbackMessageForCustomOperationName(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'sylius.resource.create_error' => 'Cannot create %resource% resource.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new Create(shortName: 'create_for_customer'))->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addErrorFlash($operation, $context);

        $this->assertSame('Cannot create Dummy resource.', $session->getFlashBag()->all()['error'][0]);
    }

    public function testItAddsErrorFlashesWithAFallbackToDeleteTranslationKeyForABulkDelete(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'sylius.resource.delete_error' => 'Cannot delete %resource% resource.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new BulkDelete())->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addErrorFlash($operation, $context);

        $this->assertSame('Cannot delete Dummy resource.', $session->getFlashBag()->all()['error'][0]);
    }

    public function testItAddsErrorFlashesWithAFallbackToDeleteTranslationKeyForABulkDeleteWithACustomOperationName(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $translator = $this->createTranslator([
            'sylius.resource.delete_error' => 'Cannot delete %resource% resource.',
        ]);

        $flashHelper = new FlashHelper($translator);

        $operation = (new BulkDelete(shortName: 'bulk_delete_for_taxon'))->withResource(new ResourceMetadata(alias: 'app.dummy', name: 'dummy', applicationName: 'app'));
        $context = new Context(new RequestOption($request));

        $flashHelper->addErrorFlash($operation, $context);

        $this->assertSame('Cannot delete Dummy resource.', $session->getFlashBag()->all()['error'][0]);
    }

    public function testItTranslatesFlashesFromEventWhenTranslatorIsNotABag(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $event = new GenericEvent();
        $event->setMessage('app.admin_user.banned');
        $event->setMessageType('success');
        $event->setMessageParameters(['%admin_user%' => 'Darth Vader']);

        $context = new Context(new RequestOption($request));

        $translator = $this->createMock(TranslatorInterface::class);
        $flashHelper = new FlashHelper($translator);

        $translator->expects($this->once())->method('trans')->with('app.admin_user.banned', ['%admin_user%' => 'Darth Vader'], 'flashes')->willReturn('Darth Vader was banned successfully.');

        $flashHelper->addFlashFromEvent($event, $context);

        $this->assertSame('Darth Vader was banned successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItTranslatesFlashesFromEventWhenTranslatorIsABag(): void
    {
        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $event = new GenericEvent();
        $event->setMessage('app.admin_user.banned');
        $event->setMessageType('success');
        $event->setMessageParameters(['%admin_user%' => 'Darth Vader']);

        $translator = $this->createTranslator([
            'app.admin_user.banned' => '%admin_user% was banned successfully.',
        ]);

        $flashHelper = new FlashHelper($translator);
        $context = new Context(new RequestOption($request));

        $flashHelper->addFlashFromEvent($event, $context);

        $this->assertSame('Darth Vader was banned successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    public function testItDoesNotTranslateEventMessageWhenTranslatorIsABagAndDoesNotContainsTheKey(): void
    {
        $translator = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $messageCatalogue = $this->createMock(MessageCatalogueInterface::class);

        $session = new Session();
        $request = new Request();
        $request->setSession($session);

        $flashHelper = new FlashHelper($translator);

        $event = new GenericEvent();
        $event->setMessage('Darth Vader was banned successfully.');
        $event->setMessageType('success');

        $context = new Context(new RequestOption($request));

        $translator->method('getCatalogue')->willReturn($messageCatalogue);
        $messageCatalogue->expects($this->once())->method('has')->with('Darth Vader was banned successfully.', 'flashes')->willReturn(false);

        $translator->expects($this->never())->method('trans');

        $flashHelper->addFlashFromEvent($event, $context);

        $this->assertSame('Darth Vader was banned successfully.', $session->getFlashBag()->all()['success'][0]);
    }

    /**
     * @param array<string, string> $flashesTranslations
     * @param array<string, string> $messagesTranslations
     */
    private function createTranslator(array $flashesTranslations = [], array $messagesTranslations = []): TranslatorInterface&TranslatorBagInterface
    {
        $translator = new Translator('en');
        $translator->addLoader('array', new ArrayLoader());

        foreach ($flashesTranslations as $key => $translation) {
            $translator->addResource('array', [
                $key => $translation,
            ], 'en', 'flashes');
        }

        foreach ($messagesTranslations as $key => $translation) {
            $translator->addResource('array', [
                $key => $translation,
            ], 'en', 'messages');
        }

        return $translator;
    }
}
