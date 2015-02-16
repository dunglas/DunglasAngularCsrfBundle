<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\Dunglas\AngularCsrfBundle\EventListener;

use Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager;
use Dunglas\AngularCsrfBundle\Routing\RouteMatcherInterface;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AngularCsrfValidationListenerSpec extends ObjectBehavior
{
    const HEADER_NAME = 'csrf';
    const VALID_TOKEN = 'valid';
    const INVALID_TOKEN = 'invalid';

    private $routes = array('^/secured');
    private $secureValidRequest;
    private $secureInvalidRequest;
    private $unsecureRequest;

    public function let(
        AngularCsrfTokenManager $tokenManager,
        RouteMatcherInterface $routeMatcher,
        Request $secureValidRequest,
        Request $secureInvalidRequest,
        Request $unsecureRequest,
        HeaderBag $validHeaders,
        HeaderBag $invalidHeaders
    ) {
        $tokenManager->isTokenValid(self::VALID_TOKEN)->willReturn(true);
        $tokenManager->isTokenValid(self::INVALID_TOKEN)->willReturn(false);

        $this->secureValidRequest = $secureValidRequest;
        $validHeaders->get(self::HEADER_NAME)->willReturn(self::VALID_TOKEN);
        $this->secureValidRequest->headers = $validHeaders;

        $this->secureInvalidRequest = $secureInvalidRequest;
        $invalidHeaders->get(self::HEADER_NAME)->willReturn(self::INVALID_TOKEN);
        $this->secureInvalidRequest->headers = $invalidHeaders;

        $this->unsecureRequest = $unsecureRequest;

        $routeMatcher->match($this->secureValidRequest, $this->routes)->willReturn(true);
        $routeMatcher->match($this->secureInvalidRequest, $this->routes)->willReturn(true);
        $routeMatcher->match($this->unsecureRequest, $this->routes)->willReturn(false);

        $this->beConstructedWith($tokenManager, $routeMatcher, $this->routes, self::HEADER_NAME);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Dunglas\AngularCsrfBundle\EventListener\AngularCsrfValidationListener');
    }

    public function it_secures(GetResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($this->secureValidRequest);

        $this->onKernelRequest($event);
    }

    public function it_throws_an_error_when_the_csrf_token_is_bad(GetResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($this->secureInvalidRequest);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException')->duringOnKernelRequest($event);
    }

    public function it_does_not_secure_on_sub_request(GetResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::SUB_REQUEST);
        $event->getRequest()->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    public function it_does_not_secure_when_it_does_not(GetResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($this->unsecureRequest);
        $event->getResponse()->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }
}
