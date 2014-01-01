<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\Dunglas\AngularCsrfBundle\Routing;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class RouteMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Dunglas\AngularCsrfBundle\Routing\RouteMatcher');
    }

    function it_should_match_path(Request $request)
    {
        $request->getPathInfo()->willReturn('/kevin')->shouldBeCalled();

        $this->match($request, array(array ('path' => '^/kev')))->shouldBe(true);
        $this->match($request, array(array ('path' => '/kevin')))->shouldBe(true);
        $this->match($request, array(array ('path' => '^/kevindunglas')))->shouldBe(false);
    }

    function it_should_match_route(Request $request)
    {
        $request->get('_route')->willReturn('dunglas')->shouldBeCalled();

        $this->match($request, array(array ('route' => '^d')))->shouldBe(true);
        $this->match($request, array(array ('route' => 'dunglas')))->shouldBe(true);
        $this->match($request, array(array ('route' => '^kevindunglas$')))->shouldBe(false);
    }

    function it_should_match_methods(Request $request)
    {
        $request->getMethod()->willReturn('POST')->shouldBeCalled();

        $this->match($request, array(array ('methods' => array('POST'))))->shouldBe(true);
        $this->match($request, array(array ('methods' => array('PUT', 'POST'))))->shouldBe(true);
        $this->match($request, array(array ()))->shouldBe(true);
        $this->match($request, array(array ('methods' => array('HEAD', 'GET'))))->shouldBe(false);
    }

    function it_should_match_both(Request $request)
    {
        $request->getPathInfo()->willReturn('/flag')->shouldBeCalled();
        $request->get('_route')->willReturn('black')->shouldBeCalled();
        $request->getMethod()->willReturn('LINK')->shouldBeCalled();

        $this->match($request, array(array ('path' => '^/fl', 'methods' => array('LINK', 'POST'))))->shouldBe(true);
        $this->match($request, array(array ('path' => 'flag', 'route' => 'black', 'methods' => array('LINK'))))->shouldBe(true);
        $this->match($request, array(array ('route' => '^black$', 'methods' => array('LINK'))))->shouldBe(true);
        $this->match($request, array(array ('path' => '/flag', 'route' => '^black')))->shouldBe(true);
        $this->match($request, array(array ('path' => 'flag', 'route' => 'black', 'methods' => array('POST'))))->shouldBe(false);
        $this->match($request, array(array ('path' => 'flag', 'route' => 'red', 'methods' => array('LINK'))))->shouldBe(false);
        $this->match($request, array(array ('path' => 'hammer', 'route' => 'black', 'methods' => array('LINK'))))->shouldBe(false);
    }
}
