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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\EventSubscriber;

use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Sylius\Resource\Exception\UnexpectedTypeException;
use Sylius\Resource\Model\CodeAwareInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

final class AddCodeFormSubscriberTest extends TestCase
{
    public function testImplementsEventSubscriberInterface(): void
    {
        $subscriber = new AddCodeFormSubscriber();

        self::assertInstanceOf(EventSubscriberInterface::class, $subscriber);
    }

    public function testSubscribesToPreSetDataEvent(): void
    {
        $events = AddCodeFormSubscriber::getSubscribedEvents();

        self::assertSame([FormEvents::PRE_SET_DATA => 'preSetData'], $events);
    }

    private function createFormEvent(mixed $data, FormInterface $form): FormEvent
    {
        $event = $this->createMock(FormEvent::class);
        $event->method('getData')->willReturn($data);
        $event->method('getForm')->willReturn($form);

        return $event;
    }

    public function testAddsEnabledCodeFieldWhenResourceIsNew(): void
    {
        $subscriber = new AddCodeFormSubscriber();
        $resource = $this->createMock(CodeAwareInterface::class);
        $resource->method('getCode')->willReturn(null);

        $form = $this->createMock(FormInterface::class);
        $form
            ->expects(self::once())
            ->method('add')
            ->with(
                'code',
                TextType::class,
                self::callback(fn (array $options): bool => $options['disabled'] === false),
            )
            ->willReturn($form);

        $subscriber->preSetData($this->createFormEvent($resource, $form));
    }

    public function testAddsDisabledCodeFieldWhenResourceHasCode(): void
    {
        $subscriber = new AddCodeFormSubscriber();
        $resource = $this->createMock(CodeAwareInterface::class);
        $resource->method('getCode')->willReturn('existing_code');

        $form = $this->createMock(FormInterface::class);
        $form
            ->expects(self::once())
            ->method('add')
            ->with(
                'code',
                TextType::class,
                self::callback(fn (array $options): bool => $options['disabled'] === true),
            )
            ->willReturn($form);

        $subscriber->preSetData($this->createFormEvent($resource, $form));
    }

    public function testAddsEnabledCodeFieldWhenResourceIsNull(): void
    {
        $subscriber = new AddCodeFormSubscriber();
        $form = $this->createMock(FormInterface::class);
        $form
            ->expects(self::once())
            ->method('add')
            ->with(
                'code',
                TextType::class,
                self::callback(fn (array $options): bool => $options['disabled'] === false),
            )
            ->willReturn($form);

        $subscriber->preSetData($this->createFormEvent(null, $form));
    }

    public function testThrowsExceptionWhenResourceDoesNotImplementCodeAwareInterface(): void
    {
        $subscriber = new AddCodeFormSubscriber();
        $event = $this->createMock(FormEvent::class);
        $event->method('getData')->willReturn(new \stdClass());

        self::expectException(UnexpectedTypeException::class);

        $subscriber->preSetData($event);
    }

    public function testUsesCustomTypeAndOptions(): void
    {
        $subscriber = new AddCodeFormSubscriber(FormType::class, ['label' => 'custom.label']);
        $resource = $this->createMock(CodeAwareInterface::class);
        $resource->method('getCode')->willReturn('code');

        $form = $this->createMock(FormInterface::class);
        $form
            ->expects(self::once())
            ->method('add')
            ->with(
                'code',
                FormType::class,
                self::callback(function (array $options): bool {
                    return $options['label'] === 'custom.label'
                        && $options['disabled'] === true;
                }),
            )
            ->willReturn($form);

        $subscriber->preSetData($this->createFormEvent($resource, $form));
    }
}
