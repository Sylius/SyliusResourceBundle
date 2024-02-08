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

namespace spec\Sylius\Resource\Metadata;

use PhpSpec\ObjectBehavior;
use Sylius\Resource\Metadata\Create;
use Sylius\Resource\Metadata\Operations;
use Sylius\Resource\Metadata\ResourceMetadata;
use Sylius\Resource\Metadata\Update;

final class ResourceMetadataSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(ResourceMetadata::class);
    }

    function it_has_no_alias_by_default(): void
    {
        $this->getAlias()->shouldReturn(null);
    }

    function it_could_have_an_alias(): void
    {
        $this->withAlias('app.book')
            ->getAlias()
            ->shouldReturn('app.book')
        ;
    }

    function it_has_no_section_by_default(): void
    {
        $this->getSection()->shouldReturn(null);
    }

    function it_could_have_a_section(): void
    {
        $this->withSection('admin')
            ->getSection()
            ->shouldReturn('admin')
        ;
    }

    function it_has_no_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_could_have_a_name(): void
    {
        $this->withName('book')
            ->getName()
            ->shouldReturn('book')
        ;
    }

    function it_has_no_application_name_by_default(): void
    {
        $this->getApplicationName()->shouldReturn(null);
    }

    function it_could_have_an_application_name(): void
    {
        $this->withApplicationName('app')
            ->getApplicationName()
            ->shouldReturn('app')
        ;
    }

    function it_has_no_operations_by_default(): void
    {
        $this->getOperations()->shouldReturn(null);
    }

    function it_could_have_operations(): void
    {
        $operations = new Operations();

        $this->withOperations($operations)
            ->getOperations()
            ->shouldReturn($operations)
        ;
    }

    function it_can_be_constructed_with_an_alias(): void
    {
        $this->beConstructedWith('app.book');

        $this->getAlias()->shouldReturn('app.book');
    }

    function it_can_be_constructed_with_a_section(): void
    {
        $this->beConstructedWith(null, 'admin');

        $this->getSection()->shouldReturn('admin');
    }

    function it_can_be_constructed_with_a_name(): void
    {
        $this->beConstructedWith(null, null, null, null, null, 'book');

        $this->getName()->shouldReturn('book');
    }

    function it_can_be_constructed_with_an_application_name(): void
    {
        $this->beConstructedWith(null, null, null, null, null, null, null, 'app');

        $this->getApplicationName()->shouldReturn('app');
    }

    function it_can_be_constructed_with_a_form_type(): void
    {
        $this->beConstructedWith(null, null, 'App\Form\DummyType');

        $this->getFormType()->shouldReturn('App\Form\DummyType');
    }

    function it_can_be_constructed_with_a_templates_dir(): void
    {
        $this->beConstructedWith(null, null, null, 'book');

        $this->getTemplatesDir()->shouldReturn('book');
    }

    function it_can_be_constructed_with_a_route_prefix(): void
    {
        $this->beConstructedWith(null, null, null, null, '/admin');

        $this->getRoutePrefix()->shouldReturn('/admin');
    }

    function it_can_be_constructed_with_a_plural_name(): void
    {
        $this->beConstructedWith(null, null, null, null, null, null, 'books');

        $this->getPluralName()->shouldReturn('books');
    }

    function it_can_be_constructed_with_an_identifier(): void
    {
        $this->beConstructedWith(null, null, null, null, null, null, null, null, 'code');

        $this->getIdentifier()->shouldReturn('code');
    }

    function it_can_be_constructed_with_a_normalization_context(): void
    {
        $this->beConstructedWith('app.book', null, null, null, null, null, null, null, null, ['groups' => ['dummy:read']]);

        $this->getNormalizationContext()->shouldReturn(['groups' => ['dummy:read']]);
    }

    function it_can_be_constructed_with_a_denormalization_context(): void
    {
        $this->beConstructedWith('app.book', null, null, null, null, null, null, null, null, null, ['groups' => ['dummy:write']]);

        $this->getDenormalizationContext()->shouldReturn(['groups' => ['dummy:write']]);
    }

    function it_can_be_constructed_with_a_validation_context(): void
    {
        $this->beConstructedWith('app.book', null, null, null, null, null, null, null, null, null, null, ['groups' => ['sylius']]);

        $this->getValidationContext()->shouldReturn(['groups' => ['sylius']]);
    }

    function it_can_be_constructed_with_a_class(): void
    {
        $this->beConstructedWith('app.book', null, null, null, null, null, null, null, null, null, null, null, 'App\Resource');

        $this->getClass()->shouldReturn('App\Resource');
    }

    function it_can_be_constructed_with_a_driver(): void
    {
        $this->beConstructedWith('app.book', null, null, null, null, null, null, null, null, null, null, null, null, 'doctrine/orm');

        $this->getDriver()->shouldReturn('doctrine/orm');
    }

    function it_can_be_constructed_with_vars(): void
    {
        $this->beConstructedWith('app.book', null, null, null, null, null, null, null, null, null, null, null, null, null, ['subheader' => 'Managing your library']);

        $this->getVars()->shouldReturn(['subheader' => 'Managing your library']);
    }

    function it_can_be_constructed_with_operations(): void
    {
        $operations = [new Create(), new Update()];

        $this->beConstructedWith(null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, $operations);

        $this->getOperations()->shouldHaveCount(2);
    }
}
