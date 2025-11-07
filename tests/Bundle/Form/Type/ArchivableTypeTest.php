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

namespace Sylius\Bundle\ResourceBundle\Tests\Form\Type;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sylius\Bundle\ResourceBundle\Form\Type\ArchivableType;
use Sylius\Resource\Model\ArchivableInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

final class ArchivableTypeTest extends TestCase
{
    private ArchivableType $formType;

    protected function setUp(): void
    {
        $this->formType = new ArchivableType();
    }

    public function testExtendsAbstractType(): void
    {
        self::assertInstanceOf(AbstractType::class, $this->formType);
    }

    public function testGetBlockPrefix(): void
    {
        self::assertSame('sylius_archivable', $this->formType->getBlockPrefix());
    }

    public function testBuildFormAddsArchivedAtField(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->expects(self::once())
            ->method('add')
            ->with('archivedAt', DateTimeType::class)
            ->willReturn($builder);

        $builder
            ->method('addEventListener')
            ->willReturn($builder);

        $this->formType->buildForm($builder, []);
    }

    public function testBuildFormAddsSubmitEventListener(): void
    {
        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->method('add')
            ->willReturn($builder);

        $builder
            ->expects(self::once())
            ->method('addEventListener')
            ->with(
                self::identicalTo('form.submit'),
                self::callback(fn ($callback): bool => is_callable($callback)),
            )
            ->willReturn($builder);

        $this->formType->buildForm($builder, []);
    }

    public function testEventListenerSetsArchivedAtWhenNull(): void
    {
        $archivable = $this->createMock(ArchivableInterface::class);
        $archivable
            ->expects(self::once())
            ->method('getArchivedAt')
            ->willReturn(null);

        $archivable
            ->expects(self::once())
            ->method('setArchivedAt')
            ->with(self::isInstanceOf(\DateTime::class));

        $listener = $this->captureEventListener();
        $event = $this->createFormEvent($archivable);

        $listener($event);
    }

    public function testEventListenerKeepsArchivedAtWhenAlreadySet(): void
    {
        $existingDate = new \DateTime('2024-01-15 10:00:00');

        $archivable = $this->createMock(ArchivableInterface::class);
        $archivable
            ->expects(self::once())
            ->method('getArchivedAt')
            ->willReturn($existingDate);

        $archivable
            ->expects(self::once())
            ->method('setArchivedAt')
            ->with(null);

        $listener = $this->captureEventListener();
        $event = $this->createFormEvent($archivable);

        $listener($event);
    }

    public function testEventListenerSetsDataOnEvent(): void
    {
        $archivable = $this->createMock(ArchivableInterface::class);
        $archivable
            ->method('getArchivedAt')
            ->willReturn(null);

        $listener = $this->captureEventListener();
        $event = $this->createFormEvent($archivable);

        $event
            ->expects(self::once())
            ->method('setData')
            ->with($archivable);

        $listener($event);
    }

    private function captureEventListener(): callable
    {
        $listener = null;

        $builder = $this->createMock(FormBuilderInterface::class);
        $builder
            ->method('add')
            ->willReturn($builder);

        $builder
            ->expects(self::once())
            ->method('addEventListener')
            ->willReturnCallback(function ($eventName, $callback) use (&$listener, $builder) {
                $listener = $callback;

                return $builder;
            });

        $this->formType->buildForm($builder, []);

        return $listener;
    }

    /** @return FormEvent&MockObject */
    private function createFormEvent(ArchivableInterface $archivable): FormEvent
    {
        $form = $this->createMock(FormInterface::class);

        $event = $this->createMock(FormEvent::class);
        $event
            ->method('getData')
            ->willReturn($archivable);
        $event
            ->method('getForm')
            ->willReturn($form);

        return $event;
    }
}
