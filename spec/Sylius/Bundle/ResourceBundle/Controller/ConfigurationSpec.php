<?php

namespace spec\Sylius\Bundle\ResourceBundle\Controller;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\ParameterBag;

/**
 * Resource controller configuration product.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ConfigurationSpec extends ObjectBehavior
{
    /**
     * @var array
     */
    private $defaultSettings;

    /**
     * @param Symfony\Component\HttpFoundation\Request $request
     */
    function let($request)
    {
        $this->beConstructedWith('sylius', 'product', 'SyliusWebBundle:Product', 'twig');
        $request->attributes = new ParameterBag();
        $request->query = new ParameterBag();
        $this->defaultSettings = array(
            'limit' => 15,
            'paginate' => 10,
            'defaultPaginate' => 15,
            'filterable' => false,
            'criteria' => array(),
            'sortable' => false,
            'sorting' => array()
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ResourceBundle\Controller\Configuration');
    }

    function it_returns_assigned_bundle_prefix()
    {
        $this->getBundlePrefix()->shouldReturn('sylius');
    }

    function it_returns_assigned_resource_name()
    {
        $this->getResourceName()->shouldReturn('product');
    }

    function it_returns_plural_resource_name()
    {
        $this->getPluralResourceName()->shouldReturn('products');
    }

    function it_returns_assigned_template_namespace()
    {
        $this->getTemplateNamespace()->shouldReturn('SyliusWebBundle:Product');
    }

    function it_returns_assigned_templating_engine()
    {
        $this->getTemplatingEngine()->shouldReturn('twig');
    }

    function it_generates_service_names()
    {
        $this->getServiceName('manager')->shouldReturn('sylius.manager.product');
        $this->getServiceName('repository')->shouldReturn('sylius.repository.product');
        $this->getServiceName('controller')->shouldReturn('sylius.controller.product');
    }

    function it_generates_event_names()
    {
        $this->getEventName('create')->shouldReturn('sylius.product.create');
        $this->getEventName('created')->shouldReturn('sylius.product.created');
    }

    function it_generates_template_names()
    {
        $this->getTemplateName('index.html')->shouldReturn('SyliusWebBundle:Product:index.html.twig');
        $this->getTemplateName('show.html')->shouldReturn('SyliusWebBundle:Product:show.html.twig');
        $this->getTemplateName('create.html')->shouldReturn('SyliusWebBundle:Product:create.html.twig');
        $this->getTemplateName('update.html')->shouldReturn('SyliusWebBundle:Product:update.html.twig');

        $this->getTemplateName('custom.html')->shouldReturn('SyliusWebBundle:Product:custom.html.twig');
    }

    function it_generates_route_names()
    {
        $this->getRouteName('index')->shouldReturn('sylius_product_index');
        $this->getRouteName('show')->shouldReturn('sylius_product_show');
        $this->getRouteName('custom')->shouldReturn('sylius_product_custom');
    }

    function its_not_sortable_by_default($request)
    {
        $this->defaultSettings['sortable'] = null;
        $this->load($request, $this->defaultSettings);
        $this->isSortable()->shouldReturn(false);
    }

    function its_not_filterable_by_default($request)
    {
        $this->defaultSettings['filterable'] = null;
        $this->load($request, $this->defaultSettings);
        $this->isFilterable()->shouldReturn(false);
    }

    function it_has_limit_equal_to_10_by_default($request)
    {
        $this->defaultSettings['limit'] = null;
        $this->load($request, $this->defaultSettings);
        $this->getLimit()->shouldReturn(10);
    }

    function its_paginated_by_default($request)
    {
        $this->defaultSettings['paginate'] = null;
        $this->load($request, $this->defaultSettings);
        $this->isPaginated()->shouldReturn(true);
    }

    function its_pagination_max_per_page_is_equal_to_the_default_paginate_value($request)
    {
        $this->defaultSettings['paginate'] = null;
        $this->load($request, $this->defaultSettings);
        $this->getPaginationMaxPerPage()->shouldReturn(15);
    }

    function its_api_request_when_format_is_not_html($request)
    {
        $this->load($request, $this->defaultSettings);

        $request->getRequestFormat()->willReturn('html');
        $this->isApiRequest()->shouldReturn(false);

        $request->getRequestFormat()->willReturn('xml');
        $this->isApiRequest()->shouldReturn(true);

        $request->getRequestFormat()->willReturn('json');
        $this->isApiRequest()->shouldReturn(true);
    }

    function it_generates_view_template_by_default($request)
    {
        $this->load($request, $this->defaultSettings);
        $this->getTemplate('create.html')->shouldReturn('SyliusWebBundle:Product:create.html.twig');
    }

    function it_gets_view_template_from_request_attributes_if_available($request)
    {
        $request->attributes->set('_sylius', array('template' => 'SyliusWebBundle:Product:custom.html.twig'));
        $this->load($request, $this->defaultSettings);

        $this->getTemplate('create.html')->shouldReturn('SyliusWebBundle:Product:custom.html.twig');
    }

    function it_generates_form_type_by_default($request)
    {
        $this->load($request, $this->defaultSettings);
        $this->getFormType()->shouldReturn('sylius_product');
    }

    function it_gets_form_type_from_request_attributes_if_available($request)
    {
        $request->attributes->set('_sylius', array('form' => 'sylius_product_custom'));
        $this->load($request, $this->defaultSettings);

        $this->getFormType()->shouldReturn('sylius_product_custom');
    }

    function it_generates_redirect_route_by_default($request)
    {
        $this->load($request, $this->defaultSettings);

        $this->getRedirectRoute('index')->shouldReturn('sylius_product_index');
        $this->getRedirectRoute('show')->shouldReturn('sylius_product_show');
        $this->getRedirectRoute('custom')->shouldReturn('sylius_product_custom');
    }

    function it_gets_redirect_route_from_request_attributes_if_available($request)
    {
        $request->attributes->set('_sylius', array('redirect' => 'sylius_product_custom'));
        $this->load($request, $this->defaultSettings);

        $this->getRedirectRoute('index')->shouldReturn('sylius_product_custom');
    }

    function it_returns_empty_array_as_redirect_parameters_by_default($request)
    {
        $this->load($request, $this->defaultSettings);
        $this->getRedirectParameters()->shouldReturn(array());
    }

    function it_gets_redirect_route_and_parameters_from_request_attributes($request)
    {
        $redirect = array(
            'route'      => 'sylius_list',
            'parameters' => array('id' => 1)
        );

        $request->attributes->set('_sylius', array('redirect' => $redirect));
        $this->load($request, $this->defaultSettings);

        $this->getRedirectRoute('index')->shouldReturn('sylius_list');
        $this->getRedirectParameters()->shouldReturn(array('id' => 1));
    }

    function it_gets_criteria_from_request_attributes($request)
    {
        $request->attributes->set('_sylius', array('criteria' => array('enabled' => false)));
        $this->load($request, $this->defaultSettings);

        $this->getCriteria()->shouldReturn(array('enabled' => false));
    }

    function it_gets_criteria_from_request_if_resources_are_filterable($request)
    {
        $request->get('criteria', Argument::any())->shouldBeCalled()->willReturn(array('locked' => false));
        $request->attributes->set('_sylius', array(
            'filterable' => true,
            'criteria'   => array('enabled' => false)
        ));

        $this->load($request, $this->defaultSettings);

        $this->getCriteria()->shouldReturn(array('enabled' => false, 'locked' => false));
    }

    function it_does_not_get_criteria_from_request_if_resources_are_not_filterable($request)
    {
        $request->get('criteria', Argument::any())->shouldNotBeCalled();
        $request->attributes->set('_sylius', array(
            'filterable' => false,
            'criteria'   => array('enabled' => false)
        ));

        $this->load($request, $this->defaultSettings);

        $this->getCriteria()->shouldReturn(array('enabled' => false));
    }

    function it_gets_sorting_from_request_if_resources_are_sortable($request)
    {
        $request->get('sorting', Argument::any())->willReturn(array('createdAt' => 'desc'));
        $request->attributes->set('_sylius', array(
            'sortable' => true,
            'sorting'  => array('name' => 'asc')
        ));

        $this->load($request, $this->defaultSettings);

        $this->getSorting()->shouldReturn(array('name' => 'asc', 'createdAt' => 'desc'));
    }

    function it_does_not_get_sorting_from_request_if_resources_are_not_sortable($request)
    {
        $request->get('sorting', Argument::any())->shouldNotBeCalled();
        $request->attributes->set('_sylius', array(
            'sortable' => false,
            'sorting'  => array('name' => 'asc')
        ));

        $this->load($request, $this->defaultSettings);

        $this->getSorting()->shouldReturn(array('name' => 'asc'));
    }

    function it_gets_sorting_from_request_attributes($request)
    {
        $request->attributes->set('_sylius', array('sorting' => array('createdAt' => 'asc')));
        $this->load($request, $this->defaultSettings);

        $this->getSorting()->shouldReturn(array('createdAt' => 'asc'));
    }

    function it_is_not_paginated_if_paginate_option_is_set_to_false($request)
    {
        $request->attributes->set('_sylius', array('paginate' => false));
        $this->load($request, $this->defaultSettings);

        $this->isPaginated()->shouldReturn(false);
    }

    function it_gets_pagination_max_per_page_from_request_attributes($request)
    {
        $request->attributes->set('_sylius', array('paginate' => 25));
        $this->load($request, $this->defaultSettings);

        $this->getPaginationMaxPerPage()->shouldReturn(25);
    }

    function it_gets_pagination_max_per_page_from_request_query_attribute($request)
    {
        $request->get('p', Argument::any())->shouldBeCalled()->willReturn(45);

        $request->attributes->set('_sylius', array('paginate' => '$p'));
        $this->load($request, $this->defaultSettings);

        $this->getPaginationMaxPerPage()->shouldReturn(45);
    }

    function it_gets_pagination_max_per_page_from_settings($request)
    {
        $this->load($request, $this->defaultSettings);

        $this->getPaginationMaxPerPage()->shouldReturn(10);
    }

    function it_gets_pagination_default_max_per_page_from_settings($request)
    {
        $request->attributes->set('_sylius', array('paginate' => '$paginate'));
        $this->load($request, $this->defaultSettings);

        $this->getPaginationMaxPerPage()->shouldReturn(15);
    }

    function it_gets_limit_from_request_attributes($request)
    {
        $request->attributes->set('_sylius', array('limit' => 20));
        $this->load($request, $this->defaultSettings);

        $this->getLimit()->shouldReturn(20);
    }

    function it_gets_limit_from_settings($request)
    {
        $this->load($request, $this->defaultSettings);

        $this->getLimit()->shouldReturn(15);
    }

    function it_returns_given_method_by_default($request)
    {
        $this->load($request, $this->defaultSettings);

        $this->getMethod('createPaginator')->shouldReturn('createPaginator');
        $this->getMethod('findBy')->shouldReturn('findBy');
    }

    function it_gets_method_from_request_attributes_if_available($request)
    {
        $request->attributes->set('_sylius', array('method' => 'findLatest'));
        $this->load($request, $this->defaultSettings);

        $this->getMethod('findBy')->shouldReturn('findLatest');
    }

    function it_returns_empty_array_as_method_arguments_by_default($request)
    {
        $this->load($request, $this->defaultSettings);
        $this->getArguments()->shouldReturn(array());
    }

    function it_gets_method_and_arguments_from_request_attributes($request)
    {
        $request->attributes->set('_sylius', array(
            'method'    => 'findLatest',
            'arguments' => array(9)
        ));

        $this->load($request, $this->defaultSettings);

        $this->getMethod('findOneBy')->shouldReturn('findLatest');
        $this->getArguments()->shouldReturn(array(9));
    }
}
