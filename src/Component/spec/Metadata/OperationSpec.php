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

namespace spec\Sylius\Component\Resource\Metadata;

use App\Form\Type\BookType;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Resource\Metadata\Operation;
use Sylius\Component\Resource\Tests\Dummy\DummyClassOne;
use Sylius\Component\Resource\Tests\Dummy\DummyController;
use Sylius\Component\Resource\Tests\Dummy\DummyProcessor;
use Sylius\Component\Resource\Tests\Dummy\DummyProvider;

final class OperationSpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Operation::class);
    }

    function it_has_no_name_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_could_have_a_name(): void
    {
        $this->withName('create')
            ->getName()
            ->shouldReturn('create')
        ;
    }

    function it_has_no_methods_by_default(): void
    {
        $this->getMethods()->shouldReturn(null);
    }

    function it_could_have_methods(): void
    {
        $this->withMethods(['POST', 'GET'])
            ->getMethods()
            ->shouldReturn(['POST', 'GET'])
        ;
    }

    function it_has_no_path_by_default(): void
    {
        $this->getName()->shouldReturn(null);
    }

    function it_could_have_a_path(): void
    {
        $this->withPath('you_should_not_pass')
            ->getPath()
            ->shouldReturn('you_should_not_pass')
        ;
    }

    function it_has_no_route_prefix_by_default(): void
    {
        $this->getRoutePrefix()->shouldReturn(null);
    }

    function it_could_have_a_route_prefix(): void
    {
        $this->withRoutePrefix('/admin')
            ->getRoutePrefix()
            ->shouldReturn('/admin')
        ;
    }

    function it_has_no_controller_by_default(): void
    {
        $this->getController()->shouldReturn(null);
    }

    function it_could_have_a_controller(): void
    {
        $this->withController(DummyController::class)
            ->getController()
            ->shouldReturn(DummyController::class)
        ;
    }

    function it_has_no_template_by_default(): void
    {
        $this->getTemplate()->shouldReturn(null);
    }

    function it_could_have_a_template(): void
    {
        $this->withTemplate('book/show.html.twig')
            ->getTemplate()
            ->shouldReturn('book/show.html.twig')
        ;
    }

    function it_has_no_repository_by_default(): void
    {
        $this->getRepository()->shouldReturn(null);
    }

    function it_could_have_a_repository_with_method(): void
    {
        $this->withRepository(['method' => 'createListQueryBuilder'])
            ->getRepository()
            ->shouldReturn(['method' => 'createListQueryBuilder'])
        ;
    }

    function it_could_have_a_repository_with_method_and_arguments(): void
    {
        $this->withRepository(['method' => 'createListQueryBuilder', 'arguments' => ['foo' => 'fighters']])
            ->getRepository()
            ->shouldReturn(['method' => 'createListQueryBuilder', 'arguments' => ['foo' => 'fighters']])
        ;
    }

    function it_has_no_criteria_by_default(): void
    {
        $this->getCriteria()->shouldReturn(null);
    }

    function it_could_have_criteria(): void
    {
        $this->withCriteria(['foo' => 'fighters'])
            ->getCriteria()
            ->shouldReturn(['foo' => 'fighters'])
        ;
    }

    function it_has_no_requirements_by_default(): void
    {
        $this->getRequirements()->shouldReturn(null);
    }

    function it_could_have_requirements(): void
    {
        $this->withRequirements(['id' => '\d+'])
            ->getRequirements()
            ->shouldReturn(['id' => '\d+'])
        ;
    }

    function it_has_no_options_by_default(): void
    {
        $this->getOptions()->shouldReturn(null);
    }

    function it_could_have_options(): void
    {
        $this->withOptions(['utf8' => true])
            ->getOptions()
            ->shouldReturn(['utf8' => true])
        ;
    }

    function it_has_no_host_by_default(): void
    {
        $this->getHost()->shouldReturn(null);
    }

    function it_could_have_a_host(): void
    {
        $this->withHost('m.example.com')
            ->getHost()
            ->shouldReturn('m.example.com')
        ;
    }

    function it_has_no_schemes_by_default(): void
    {
        $this->getSchemes()->shouldReturn(null);
    }

    function it_could_have_schemes(): void
    {
        $this->withSchemes(['https'])
            ->getSchemes()
            ->shouldReturn(['https'])
        ;
    }

    function it_has_no_priority_by_default(): void
    {
        $this->getPriority()->shouldReturn(null);
    }

    function it_could_have_a_priority(): void
    {
        $this->withPriority(42)
            ->getPriority()
            ->shouldReturn(42)
        ;
    }

    function it_has_no_vars_by_default(): void
    {
        $this->getVars()->shouldReturn(null);
    }

    function it_could_have_vars(): void
    {
        $this->withVars([
                'all' => [
                    'subheader' => 'app.ui.manage_your_books',
                ],
            ])
            ->getVars()
            ->shouldReturn([
                'all' => [
                    'subheader' => 'app.ui.manage_your_books',
                ],
            ])
        ;
    }

    function it_has_no_form_by_default(): void
    {
        $this->getForm()->shouldReturn(null);
    }

    function it_could_have_a_form_type(): void
    {
        $this->withForm(BookType::class)
            ->getForm()
            ->shouldReturn(BookType::class)
        ;
    }

    function it_could_have_a_form_with_type_and_validation_groups(): void
    {
        $this->withForm(['type' => BookType::class, 'validation_groups' => 'my_custom_group'])
            ->getForm()
            ->shouldReturn(['type' => BookType::class, 'validation_groups' => 'my_custom_group'])
        ;
    }

    function it_could_disable_form(): void
    {
        $this->withForm(false)
            ->getForm()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_factory_by_default(): void
    {
        $this->getFactory()->shouldReturn(null);
    }

    function it_could_have_a_factory(): void
    {
        $this->withFactory('createForCustomer')
            ->getFactory()
            ->shouldReturn('createForCustomer')
        ;
    }

    function it_could_have_a_factory_with_method_and_arguments(): void
    {
        $this->withFactory(['method' => 'createForCustomer', 'arguments' => ['$customerId']])
            ->getFactory()
            ->shouldReturn(['method' => 'createForCustomer', 'arguments' => ['$customerId']])
        ;
    }

    function it_could_disable_factory(): void
    {
        $this->withFactory(false)
            ->getFactory()
            ->shouldReturn(false)
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

    function it_has_no_grid_by_default(): void
    {
        $this->getGrid()->shouldReturn(null);
    }

    function it_could_have_a_grid(): void
    {
        $this->withGrid('app_book')
            ->getGrid()
            ->shouldReturn('app_book')
        ;
    }

    function it_has_no_permission_by_default(): void
    {
        $this->hasPermission()->shouldReturn(null);
    }

    function its_permission_could_be_enabled(): void
    {
        $this->withPermission(true)
            ->hasPermission()
            ->shouldReturn(true)
        ;
    }

    function its_permission_could_be_disabled(): void
    {
        $this->withPermission(false)
            ->hasPermission()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_csrf_protection_by_default(): void
    {
        $this->hasCsrfProtection()->shouldReturn(null);
    }

    function its_csrf_protection_could_be_enabled(): void
    {
        $this->withCsrfProtection(true)
            ->hasCsrfProtection()
            ->shouldReturn(true)
        ;
    }

    function its_csrf_protection_could_be_disabled(): void
    {
        $this->withCsrfProtection(false)
            ->hasCsrfProtection()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_redirection_by_default(): void
    {
        $this->getRedirect()->shouldReturn(null);
    }

    function it_could_have_a_redirection(): void
    {
        $this->withRedirect('update')
            ->getRedirect()
            ->shouldReturn('update')
        ;
    }

    function it_could_have_a_redirection_with_route_and_parameters(): void
    {
        $this->withRedirect(['route' => 'app_admin_book_show', 'parameters' => ['id' => 'resource.id']])
            ->getRedirect()
            ->shouldReturn(['route' => 'app_admin_book_show', 'parameters' => ['id' => 'resource.id']])
        ;
    }

    function it_has_no_state_machine_by_default(): void
    {
        $this->getStateMachine()->shouldReturn(null);
    }

    function it_could_have_a_state_machine_with_transition(): void
    {
        $this->withStateMachine(['transition' => 'publish'])
            ->getStateMachine()
            ->shouldReturn(['transition' => 'publish'])
        ;
    }

    function it_could_have_a_state_machine_with_transition_and_graph(): void
    {
        $this->withStateMachine(['transition' => 'publish', 'graph' => 'app_book'])
            ->getStateMachine()
            ->shouldReturn(['transition' => 'publish', 'graph' => 'app_book'])
        ;
    }

    function it_has_no_event_by_default(): void
    {
        $this->getEvent()->shouldReturn(null);
    }

    function it_could_have_an_event(): void
    {
        $this->withEvent('publish')
            ->getEvent()
            ->shouldReturn('publish')
        ;
    }

    function it_has_no_return_content_by_default(): void
    {
        $this->hasReturnContent()->shouldReturn(null);
    }

    function it_could_have_return_content(): void
    {
        $this->withReturnContent(true)
            ->hasReturnContent()
            ->shouldReturn(true)
        ;
    }

    function it_could_have_no_return_content(): void
    {
        $this->withReturnContent(false)
            ->hasReturnContent()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_resource_by_default(): void
    {
        $this->getResource()->shouldReturn(null);
    }

    function it_could_have_a_resource(): void
    {
        $this->withResource('app.book')
            ->getResource()
            ->shouldReturn('app.book')
        ;
    }

    function it_has_no_provider_by_default(): void
    {
        $this->getProvider()->shouldReturn(null);
    }

    function it_could_have_a_provider(): void
    {
        $this->withProvider(DummyProvider::class)
            ->getProvider()
            ->shouldReturn(DummyProvider::class)
        ;
    }

    function it_has_no_processor_by_default(): void
    {
        $this->getProcessor()->shouldReturn(null);
    }

    function it_could_have_a_processor(): void
    {
        $this->withProcessor(DummyProcessor::class)
            ->getProcessor()
            ->shouldReturn(DummyProcessor::class)
        ;
    }

    function it_has_no_read_by_default(): void
    {
        $this->canRead()->shouldReturn(null);
    }

    function it_could_have_read_enabled(): void
    {
        $this->withRead(true)
            ->canRead()
            ->shouldReturn(true)
        ;
    }

    function it_could_have_read_disabled(): void
    {
        $this->withRead(false)
            ->canRead()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_validate_by_default(): void
    {
        $this->canValidate()->shouldReturn(null);
    }

    function it_could_have_validate_enabled(): void
    {
        $this->withValidate(true)
            ->canValidate()
            ->shouldReturn(true)
        ;
    }

    function it_could_have_validate_disabled(): void
    {
        $this->withValidate(false)
            ->canValidate()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_write_by_default(): void
    {
        $this->canWrite()->shouldReturn(null);
    }

    function it_could_have_write_enabled(): void
    {
        $this->withWrite(true)
            ->canWrite()
            ->shouldReturn(true)
        ;
    }

    function it_could_have_write_disabled(): void
    {
        $this->withWrite(false)
            ->canWrite()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_respond_by_default(): void
    {
        $this->canRespond()->shouldReturn(null);
    }

    function it_could_have_respond_enabled(): void
    {
        $this->withRespond(true)
            ->canRespond()
            ->shouldReturn(true)
        ;
    }

    function it_could_have_respond_disabled(): void
    {
        $this->withRespond(false)
            ->canRespond()
            ->shouldReturn(false)
        ;
    }

    function it_has_no_input_by_default(): void
    {
        $this->getInput()->shouldReturn(null);
    }

    function it_could_have_an_input(): void
    {
        $this->withInput(DummyClassOne::class)
            ->getInput()
            ->shouldReturn(DummyClassOne::class)
        ;
    }
}
