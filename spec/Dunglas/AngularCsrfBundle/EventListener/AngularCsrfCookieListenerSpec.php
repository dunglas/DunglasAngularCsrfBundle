<?php

namespace spec\Dunglas\AngularCsrfBundle\EventListener;

use Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Security\Csrf\CsrfToken;

class AngularCsrfCookieListenerSpec extends ObjectBehavior
{
    const COOKIE_NAME = 'cookie';
    const COOKIE_EXPIRE = 0;
    const COOKIE_PATH = '/';
    const COOKIE_DOMAIN = 'example.com';
    const COOKIE_SECURE = true;
    const TOKEN_VALUE = 'token';

    private $paths = array('^/punk', '^/rock$');

    public function let(AngularCsrfTokenManager $tokenManager, CsrfToken $token)
    {
        $token->getValue()->willReturn(self::TOKEN_VALUE);
        $tokenManager->getToken()->willReturn($token);

        $this->beConstructedWith(
            $tokenManager,
            $this->paths,
            self::COOKIE_NAME,
            self::COOKIE_EXPIRE,
            self::COOKIE_PATH,
            self::COOKIE_DOMAIN,
            self::COOKIE_SECURE
        );
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Dunglas\AngularCsrfBundle\EventListener\AngularCsrfCookieListener');
    }

    public function it_sets_cookie_when_it_does(
        FilterResponseEvent $event,
        Request $request,
        Response $response,
        ResponseHeaderBag $headers
    )
    {
        $request->getPathInfo()->willReturn('/punk');

        $headers->setCookie(Argument::type('Symfony\Component\HttpFoundation\Cookie'));
        $response->headers = $headers;

        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($request);
        $event->getResponse()->willReturn($response);

        $this->onKernelResponse($event);
    }

    public function it_does_not_set_cookie_on_sub_request(FilterResponseEvent $event)
    {
        $event->getRequestType()->willReturn(HttpKernelInterface::SUB_REQUEST);
        $event->getRequest()->shouldNotBeCalled();

        $this->onKernelResponse($event);
    }

    public function it_does_not_set_cookie_when_it_does_not(FilterResponseEvent $event, Request $request)
    {
        $request->getPathInfo()->willReturn('/rocknroll');

        $event->getRequestType()->willReturn(HttpKernelInterface::MASTER_REQUEST);
        $event->getRequest()->willReturn($request);
        $event->getResponse()->shouldNotBeCalled();

        $this->onKernelResponse($event);
    }
}
