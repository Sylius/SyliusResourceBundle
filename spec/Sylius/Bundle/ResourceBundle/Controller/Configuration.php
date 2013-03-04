<?php

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use PHPSpec2\ObjectBehavior;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Resource controller configuration spec.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Configuration extends ObjectBehavior
{
    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    function let($request)
    {
        $this->beConstructedWith('sylius_resource', 'spec', 'SyliusResourceBundle:Test');

        $request->attributes = new ParameterBag();
    }

    function it_should_be_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ResourceBundle\Controller\Configuration');
    }

    function it_should_return_assigned_bundle_prefix()
    {
        $this->getBundlePrefix()->shouldReturn('sylius_resource');
    }

    function it_should_return_assigned_resource_name()
    {
        $this->getResourceName()->shouldReturn('spec');
    }

    function it_should_return_plural_resource_name()
    {
        $this->getPluralResourceName()->shouldReturn('specs');
    }

    function it_should_return_assigned_template_namespace()
    {
        $this->getTemplateNamespace()->shouldReturn('SyliusResourceBundle:Test');
    }

    function it_should_generate_correct_service_names()
    {
        $this->getServiceName('manager')->shouldReturn('sylius_resource.manager.spec');
        $this->getServiceName('repository')->shouldReturn('sylius_resource.repository.spec');
        $this->getServiceName('controller')->shouldReturn('sylius_resource.controller.spec');
    }

    function it_should_generate_correct_event_name()
    {
        $this->getEventName('create')->shouldReturn('sylius_resource.spec.create');
        $this->getEventName('created')->shouldReturn('sylius_resource.spec.created');
    }

    function it_should_return_id_as_the_default_identifier_name()
    {
        $this->getIdentifierName()->shouldReturn('id');
    }

    function it_should_not_recognize_resources_as_sortable_by_default()
    {
        $this->isSortable()->shouldReturn(false);
    }

    function it_should_not_recognize_resources_as_filterable_by_default()
    {
        $this->isFilterable()->shouldReturn(false);
    }

    function it_should_return_default_resources_limit_which_is_10()
    {
        $this->getLimit()->shouldReturn(10);
    }

    function it_should_recognize_resources_as_paginated_by_default()
    {
        $this->isPaginated()->shouldReturn(true);
    }

    function it_should_return_default_pagination_max_per_page_which_is_10()
    {
        $this->getPaginationMaxPerPage()->shouldReturn(10);
    }

    function it_should_complain_if_trying_to_check_request_type_when_request_is_not_set()
    {
        $this
            ->shouldThrow('BadMethodCallException')
            ->duringIsHtmlRequest()
        ;
    }

    function it_should_recognize_request_as_html_request_when_its_the_correct_format($request)
    {
        $request->getRequestFormat()->willReturn('html');
        $this->setRequest($request);

        $this->isHtmlRequest()->shouldReturn(true);
    }

    function it_should_not_recognize_request_as_html_request_when_its_not_the_correctformat($request)
    {
        $request->geRequestFormat()->willReturn('json');

        $this->setRequest($request);
        $this->isHtmlRequest()->shouldReturn(false);
    }

    function it_should_recognize_request_as_api_request_when_format_is_not_html($request)
    {
        $this->setRequest($request);

        $request->getRequestFormat()->willReturn('html');
        $this->isApiRequest()->shouldReturn(false);

        $request->getRequestFormat()->willReturn('xml');
        $this->isApiRequest()->shouldReturn(true);
    }

    function it_should_get_identifier_name_from_request_attributes($request)
    {
        $request->attributes->set('identifier', 'slug');

        $this->setRequest($request);
        $this->getIdentifierName()->shouldReturn('slug');
    }

    function it_should_get_identifier_value_from_request($request)
    {
        $request->get('id')->willReturn('test-slug');

        $this->setRequest($request);
        $this->getIdentifierValue()->shouldReturn('test-slug');
    }

    function it_should_complain_if_trying_to_get_identifier_criteria_without_request_being_known()
    {
        $this
            ->shouldThrow(new \BadMethodCallException('Request is unknown, cannot get single resource criteria'))
            ->duringGetIdentifierCriteria()
        ;
    }

    function it_should_get_identifier_criteria_from_request($request)
    {
        $request->attributes->set('identifier', 'slug');
        $request->get('slug')->willReturn('test-slug');

        $this->setRequest($request);
        $this->getIdentifierCriteria()->shouldReturn(array('slug' => 'test-slug'));
    }

    function it_should_get_view_template_from_request_attributes($request)
    {
        $request->attributes->set('template', 'SyliusResourceBundle:Test:custom.html.twig');

        $this->setRequest($request);
        $this->getTemplate()->shouldReturn('SyliusResourceBundle:Test:custom.html.twig');
    }

    function it_should_generate_form_type_by_default()
    {
        $this->getFormType()->shouldReturn('sylius_resource_spec');
    }

    function it_should_get_form_type_from_request_attributes($request)
    {
        $request->attributes->set('form', 'sylius_resource_spec_custom');

        $this->setRequest($request);
        $this->getFormType()->shouldReturn('sylius_resource_spec_custom');
    }

    function it_should_get_redirect_route_from_request_attributes($request)
    {
        $request->attributes->set('redirect', 'sylius_resource_list');

        $this->setRequest($request);
        $this->getRedirectRoute()->shouldReturn('sylius_resource_list');
    }

    function it_should_return_empty_array_as_redirect_parameters_by_default()
    {
        $this->getRedirectParameters()->shouldReturn(array());
    }

    function it_should_get_redirect_route_and_parameters_from_request_attributes($request)
    {
        $redirect = array(
            'route'      => 'sylius_resource_list',
            'parameters' => array('id' => 1)
        );

        $request->attributes->set('redirect', $redirect);

        $this->setRequest($request);

        $this->getRedirectRoute()->shouldReturn('sylius_resource_list');
        $this->getRedirectParameters()->shouldReturn(array('id' => 1));
    }

    function it_should_get_special_redirect_parameters_from_request($request)
    {
        $redirect = array(
            'route'      => 'sylius_resource_list',
            'parameters' => array('id' => '$resourceId')
        );

        $request->attributes->set('redirect', $redirect);
        $request->get('resourceId')->shouldBeCalled()->willReturn(16);

        $this->setRequest($request);

        $this->getRedirectRoute()->shouldReturn('sylius_resource_list');
        $this->getRedirectParameters()->shouldReturn(array('id' => 16));
    }

    function it_should_get_criteria_from_request_attributes($request)
    {
        $request->attributes->set('criteria', array('enabled' => false));

        $this->setRequest($request);
        $this->getCriteria()->shouldReturn(array('enabled' => false));
    }

    function it_should_get_criteria_from_request_if_resources_are_filterable($request)
    {
        $request->get('criteria', ANY_ARGUMENT)->shouldBeCalled()->willReturn(array('locked' => false));
        $request->attributes->set('criteria', array('enabled' => false));
        $request->attributes->set('filterable', true);

        $this->setRequest($request);
        $this->getCriteria()->shouldReturn(array('locked' => false));
    }

    function it_should_not_get_criteria_from_request_if_resources_are_not_filterable($request)
    {
        $request->get('criteria', ANY_ARGUMENT)->shouldNotBeCalled();
        $request->attributes->set('criteria', array('enabled' => false));
        $request->attributes->set('filterable', false);

        $this->setRequest($request);
        $this->getCriteria()->shouldReturn(array('enabled' => false));
    }

    function it_should_get_sorting_from_request_if_resources_are_sortable($request)
    {
        $request->get('sorting', ANY_ARGUMENT)->willReturn(array('createdAt' => 'desc'));
        $request->attributes->set('sorting', array('name' => 'asc'));
        $request->attributes->set('sortable', true);

        $this->setRequest($request);
        $this->getSorting()->shouldReturn(array('createdAt' => 'desc'));
    }

    function it_should_not_get_sorting_from_request_if_resources_are_not_sortable($request)
    {
        $request->get('sorting', ANY_ARGUMENT)->shouldNotBeCalled();
        $request->attributes->set('sorting', array('name' => 'asc'));
        $request->attributes->set('sortable', false);

        $this->setRequest($request);
        $this->getSorting()->shouldReturn(array('name' => 'asc'));
    }

    function it_should_get_sorting_from_request_attributes($request)
    {
        $request->attributes->set('sorting', array('createdAt' => 'asc'));

        $this->setRequest($request);
        $this->getSorting()->shouldReturn(array('createdAt' => 'asc'));
    }

    function it_should_recognize_resources_as_not_paginated_from_request_attributes($request)
    {
        $request->attributes->set('paginate', false);

        $this->setRequest($request);
        $this->isPaginated()->shouldReturn(false);
    }

    function it_should_return_pagination_max_per_page_from_request_attributes($request)
    {
        $request->attributes->set('paginate', 25);

        $this->setRequest($request);
        $this->getPaginationMaxPerPage()->shouldReturn(25);
    }

    function it_should_return_resources_limit_from_request_attributes($request, $attributes)
    {
        $request->attributes->set('limit', 20);

        $this->setRequest($request);
        $this->getLimit()->shouldReturn(20);
    }
}
