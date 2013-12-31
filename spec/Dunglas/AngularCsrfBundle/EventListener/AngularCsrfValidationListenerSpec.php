<?php

namespace spec\Dunglas\AngularCsrfBundle\EventListener;

use Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpFoundation\HeaderBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class AngularCsrfValidationListenerSpec extends ObjectBehavior
{
    const HEADER_NAME = 'csrf';
    const VALID_TOKEN = 'valid';
    const INVALID_TOKEN = 'invalid';

    private $paths = array('^/secured');

    public function let(AngularCsrfTokenManager $tokenManager)
    {
        $tokenManager->isTokenValid(self::VALID_TOKEN)->willReturn(true);
        $tokenManager->isTokenValid(self::INVALID_TOKEN)->willReturn(false);

        $this->beConstructedWith($tokenManager, $this->paths, self::HEADER_NAME);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Dunglas\AngularCsrfBundle\EventListener\AngularCsrfValidationListener');
    }

    public function it_secures(GetResponseEvent $event, Request $request, HeaderBag $headers)
    {
        $headers->get(self::HEADER_NAME)->willReturn(self::VALID_TOKEN);
        $request->headers = $headers;
        $request->getPathInfo()->willReturn('/secured');

        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($request);

        $this->onKernelRequest($event);
    }

    public function it_throws_an_error_when_the_csrf_token_is_bad(GetResponseEvent $event, Request $request, HeaderBag $headers)
    {
        $headers->get(self::HEADER_NAME)->willReturn(self::INVALID_TOKEN);
        $request->headers = $headers;
        $request->getPathInfo()->willReturn('/secured');

        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($request);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException')->duringOnKernelRequest($event);
    }

    public function it_does_not_secure_on_sub_request(GetResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::SUB_REQUEST);
        $event->getRequest()->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }

    public function it_does_not_secure_when_it_does_not(GetResponseEvent $event, Request $request)
    {
        $request->getPathInfo()->willReturn('/notsecured');

        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($request);
        $event->getResponse()->shouldNotBeCalled();

        $this->onKernelRequest($event);
    }
}
