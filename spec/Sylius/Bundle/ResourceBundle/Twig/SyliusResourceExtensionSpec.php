<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\ResourceBundle\Twig;

use PhpSpec\ObjectBehavior;

/**
 * Sylius resource extension for Twig spec.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class SyliusResourceExtensionSpec extends ObjectBehavior
{
    /**
     * @param Symfony\Component\Routing\RouterInterface $router
     */
    function let($router)
    {
        $this->beConstructedWith($router, array(
            'limit' => 10,
            'paginate' => 10,
            'defaultPaginate' => 10,
            'filterable' => false,
            'criteria' => array(),
            'sortable' => false,
            'sorting' => array()
        ));
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\ResourceBundle\Twig\SyliusResourceExtension');
    }

    function it_is_a_Twig_extension()
    {
        $this->shouldHaveType('Twig_Extension');
    }

    function it_should_return_the_label()
    {
        $this->renderSortingLink('', 'label')
            ->shouldReturn('label');
    }
}
