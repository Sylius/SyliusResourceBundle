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

namespace Sylius\Bundle\ResourceBundle\Tests\Controller;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelper;
use Sylius\Bundle\ResourceBundle\Controller\FlashHelperInterface;
use Sylius\Bundle\ResourceBundle\Controller\RequestConfiguration;
use Sylius\Bundle\ResourceBundle\Event\ResourceControllerEvent;
use Sylius\Resource\Metadata\MetadataInterface;
use Sylius\Resource\Model\ResourceInterface;
use Sylius\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Translation\MessageCatalogueInterface;
use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class FlashHelperTest extends TestCase
{
    /** @var RequestStack|MockObject */
    private MockObject $requestStackMock;

    /** @var SessionInterface|MockObject */
    private MockObject $sessionMock;

    private MockObject $translatorMock;

    private FlashHelper $flashHelper;

    protected function setUp(): void
    {
        $this->requestStackMock = $this->createMock(RequestStack::class);
        $this->sessionMock = $this->createMock(SessionInterface::class);
        $this->translatorMock = $this->createMock(TranslatorInterface::class);
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->flashHelper = new FlashHelper($this->requestStackMock, $this->translatorMock, 'en');

            return;
        }
        $this->flashHelper = new FlashHelper($this->sessionMock, $this->translatorMock, 'en');
    }

    public function testImplementsFlashHelperInterface(): void
    {
        $this->assertInstanceOf(FlashHelperInterface::class, $this->flashHelper);
    }

    public function testAddsResourceMessageByDefault(): void
    {
        /** @var MessageCatalogueInterface|MockObject $messageCatalogueMock */
        $messageCatalogueMock = $this->createMock(MessageCatalogueInterface::class);
        /** @var FlashBagInterface|MockObject $flashBagMock */
        $flashBagMock = $this->createMock(FlashBagInterface::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);

        $this->translatorMock = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $translator = $this->translatorMock;
        assert($translator instanceof TranslatorInterface);
        $this->flashHelper = new FlashHelper($this->requestStackMock, $translator, 'en');

        $metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $requestConfigurationMock->expects($this->once())->method('getFlashMessage')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $this->translatorMock->expects($this->once())->method('getCatalogue')->with('en')->willReturn($messageCatalogueMock);
        $messageCatalogueMock->expects($this->once())->method('has')->with('sylius.product.create', 'flashes')->willReturn(false);
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->requestStackMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        }
        $this->sessionMock->expects($this->once())->method('getBag')->with('flashes')->willReturn($flashBagMock);
        $flashBagMock->expects($this->once())->method('add')->with('success', [
            'message' => 'sylius.resource.create',
            'parameters' => ['%resource%' => 'Product'],
        ]);
        $this->flashHelper->addSuccessFlash($requestConfigurationMock, ResourceActions::CREATE, $resourceMock);
    }

    public function testAddsResourceMessageWhenCatalogueIsUnavailableAndGivenMessageCannotBeTranslated(): void
    {
        /** @var FlashBagInterface|MockObject $flashBagMock */
        $flashBagMock = $this->createMock(FlashBagInterface::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $parameters = ['%resource%' => 'Product'];
        $metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $requestConfigurationMock->expects($this->once())->method('getFlashMessage')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $this->translatorMock->expects($this->once())->method('trans')->with('sylius.product.create', $parameters, 'flashes')->willReturn('sylius.product.create');
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->requestStackMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        }
        $this->sessionMock->expects($this->once())->method('getBag')->with('flashes')->willReturn($flashBagMock);
        $flashBagMock->expects($this->once())->method('add')->with('success', [
            'message' => 'sylius.resource.create',
            'parameters' => $parameters,
        ]);
        $this->flashHelper->addSuccessFlash($requestConfigurationMock, ResourceActions::CREATE, $resourceMock);
    }

    public function testAddsResourceMessageWhenCatalogueIsUnavailableAndGivenMessageCanBeTranslated(): void
    {
        /** @var FlashBagInterface|MockObject $flashBagMock */
        $flashBagMock = $this->createMock(FlashBagInterface::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);
        $parameters = ['%resource%' => 'Spoon'];
        $metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('spoon');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $requestConfigurationMock->expects($this->once())->method('getFlashMessage')->with(ResourceActions::CREATE)
            ->willReturn('%resource% is the best cutlery of them all!')
        ;
        $this->translatorMock->expects($this->once())->method('trans')->with('%resource% is the best cutlery of them all!', $parameters, 'flashes')
            ->willReturn('Spoon is the best cutlery of them all!')
        ;
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->requestStackMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        }
        $this->sessionMock->expects($this->once())->method('getBag')->with('flashes')->willReturn($flashBagMock);
        $flashBagMock->expects($this->once())->method('add')->with('success', [
            'message' => '%resource% is the best cutlery of them all!',
            'parameters' => $parameters,
        ]);
        $this->flashHelper->addSuccessFlash($requestConfigurationMock, ResourceActions::CREATE, $resourceMock);
    }

    public function testAddsResourceMessageIfMessageWasNotFoundInTheCatalogue(): void
    {
        /** @var MessageCatalogueInterface|MockObject $messageCatalogueMock */
        $messageCatalogueMock = $this->createMock(MessageCatalogueInterface::class);
        /** @var FlashBagInterface|MockObject $flashBagMock */
        $flashBagMock = $this->createMock(FlashBagInterface::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);

        /** @var ResourceInterface|MockObject $resourceMock */
        $this->translatorMock = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $translator = $this->translatorMock;
        assert($translator instanceof TranslatorInterface);
        $this->flashHelper = new FlashHelper($this->requestStackMock, $translator, 'en');

        $metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $requestConfigurationMock->expects($this->once())->method('getFlashMessage')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $this->translatorMock->expects($this->once())->method('getCatalogue')->with('en')->willReturn($messageCatalogueMock);
        $messageCatalogueMock->expects($this->once())->method('has')->with('sylius.product.create', 'flashes')->willReturn(false);
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->requestStackMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        }
        $this->sessionMock->expects($this->once())->method('getBag')->with('flashes')->willReturn($flashBagMock);
        $flashBagMock->expects($this->once())->method('add')->with('success', [
            'message' => 'sylius.resource.create',
            'parameters' => ['%resource%' => 'Product'],
        ]);
        $this->flashHelper->addSuccessFlash($requestConfigurationMock, ResourceActions::CREATE, $resourceMock);
    }

    public function testAddsOverwrittenMessage(): void
    {
        /** @var MessageCatalogueInterface|MockObject $messageCatalogueMock */
        $messageCatalogueMock = $this->createMock(MessageCatalogueInterface::class);
        /** @var FlashBagInterface|MockObject $flashBagMock */
        $flashBagMock = $this->createMock(FlashBagInterface::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);

        $this->translatorMock = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $translator = $this->translatorMock;
        assert($translator instanceof TranslatorInterface);
        $this->flashHelper = new FlashHelper($this->requestStackMock, $translator, 'en');

        $metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('product');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $requestConfigurationMock->expects($this->once())->method('getFlashMessage')->with(ResourceActions::CREATE)->willReturn('sylius.product.create');
        $this->translatorMock->expects($this->once())->method('getCatalogue')->with('en')->willReturn($messageCatalogueMock);
        $messageCatalogueMock->expects($this->once())->method('has')->with('sylius.product.create', 'flashes')->willReturn(true);
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->requestStackMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        }
        $this->sessionMock->expects($this->once())->method('getBag')->with('flashes')->willReturn($flashBagMock);
        $flashBagMock->expects($this->once())->method('add')->with('success', 'sylius.product.create');
        $this->flashHelper->addSuccessFlash($requestConfigurationMock, ResourceActions::CREATE, $resourceMock);
    }

    public function testAddsCustomMessage(): void
    {
        /** @var MessageCatalogueInterface|MockObject $messageCatalogueMock */
        $messageCatalogueMock = $this->createMock(MessageCatalogueInterface::class);
        /** @var FlashBagInterface|MockObject $flashBagMock */
        $flashBagMock = $this->createMock(FlashBagInterface::class);
        /** @var MetadataInterface|MockObject $metadataMock */
        $metadataMock = $this->createMock(MetadataInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceInterface|MockObject $resourceMock */
        $resourceMock = $this->createMock(ResourceInterface::class);

        $this->translatorMock = $this->createMockForIntersectionOfInterfaces([TranslatorInterface::class, TranslatorBagInterface::class]);
        $translator = $this->translatorMock;
        assert($translator instanceof TranslatorInterface);
        $this->flashHelper = new FlashHelper($this->requestStackMock, $translator, 'en');

        $metadataMock->expects($this->once())->method('getHumanizedName')->willReturn('book');
        $requestConfigurationMock->expects($this->once())->method('getMetadata')->willReturn($metadataMock);
        $requestConfigurationMock->expects($this->once())->method('getFlashMessage')->with('send')->willReturn('app.book.send');
        $this->translatorMock->expects($this->once())->method('getCatalogue')->with('en')->willReturn($messageCatalogueMock);
        $messageCatalogueMock->expects($this->once())->method('has')->with('app.book.send', 'flashes')->willReturn(true);
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->requestStackMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        }
        $this->sessionMock->expects($this->once())->method('getBag')->with('flashes')->willReturn($flashBagMock);
        $flashBagMock->expects($this->once())->method('add')->with('success', 'app.book.send');
        $this->flashHelper->addSuccessFlash($requestConfigurationMock, 'send', $resourceMock);
    }

    public function testAddsMessageFromEvent(): void
    {
        /** @var FlashBagInterface|MockObject $flashBagMock */
        $flashBagMock = $this->createMock(FlashBagInterface::class);
        /** @var RequestConfiguration|MockObject $requestConfigurationMock */
        $requestConfigurationMock = $this->createMock(RequestConfiguration::class);
        /** @var ResourceControllerEvent|MockObject $eventMock */
        $eventMock = $this->createMock(ResourceControllerEvent::class);
        $eventMock->expects($this->once())->method('getMessage')->willReturn('sylius.channel.cannot_be_deleted');
        $eventMock->expects($this->once())->method('getMessageType')->willReturn(ResourceControllerEvent::TYPE_WARNING);
        $eventMock->expects($this->once())->method('getMessageParameters')->willReturn(['%name%' => 'Germany Sylius Webshop']);
        if (method_exists(RequestStack::class, 'getSession')) {
            $this->requestStackMock->expects($this->once())->method('getSession')->willReturn($this->sessionMock);
        }
        $this->sessionMock->expects($this->once())->method('getBag')->with('flashes')->willReturn($flashBagMock);
        $flashBagMock->expects($this->once())->method('add')->with(ResourceControllerEvent::TYPE_WARNING, [
            'message' => 'sylius.channel.cannot_be_deleted',
            'parameters' => ['%name%' => 'Germany Sylius Webshop'],
        ]);
        $this->flashHelper->addFlashFromEvent($requestConfigurationMock, $eventMock);
    }
}
