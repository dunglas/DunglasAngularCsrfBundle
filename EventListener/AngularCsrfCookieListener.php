<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\EventListener;

use Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager;
use Dunglas\AngularCsrfBundle\Routing\RouteMatcherInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Sets a cookie containing the CSRF token.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AngularCsrfCookieListener
{
    /**
     * @var AngularCsrfTokenManager
     */
    protected $angularCsrfTokenManager;
    /**
     * @var array
     */
    protected $routes;
    /**
     * @var string
     */
    protected $cookieName;
    /**
     * @var int
     */
    protected $cookieExpire;
    /**
     * @var string
     */
    protected $cookiePath;
    /**
     * @var string
     */
    protected $cookieDomain;
    /**
     * @var bool
     */
    protected $cookieSecure;

    /**
     * @param AngularCsrfTokenManager $angularCsrfTokenManager
     * @param RouteMatcherInterface   $routeMatcher
     * @param array                   $routes
     * @param string                  $cookieName
     * @param int                     $cookieExpire
     * @param string                  $cookiePath
     * @param string                  $cookieDomain
     * @param bool                    $cookieSecure
     */
    public function __construct(
        AngularCsrfTokenManager $angularCsrfTokenManager,
        RouteMatcherInterface $routeMatcher,
        array $routes,
        $cookieName,
        $cookieExpire,
        $cookiePath,
        $cookieDomain,
        $cookieSecure
    ) {
        $this->angularCsrfTokenManager = $angularCsrfTokenManager;
        $this->routeMatcher = $routeMatcher;
        $this->routes = $routes;
        $this->cookieName = $cookieName;
        $this->cookieExpire = $cookieExpire;
        $this->cookiePath = $cookiePath;
        $this->cookieDomain = $cookieDomain;
        $this->cookieSecure = $cookieSecure;
    }

    /**
     * Sets a cookie to the response containing the CRSF token.
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (
            HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()
            ||
            !$this->routeMatcher->match($event->getRequest(), $this->routes)
        ) {
            return;
        }
        $event->getResponse()->headers->setCookie(new Cookie(
            $this->cookieName,
            $this->angularCsrfTokenManager->getToken()->getValue(),
            0 === $this->cookieExpire ? $this->cookieExpire : time() + $this->cookieExpire,
            $this->cookiePath,
            $this->cookieDomain,
            $this->cookieSecure,
            false
        ));
    }
}
