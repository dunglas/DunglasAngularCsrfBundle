<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\EventListener;

use Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\HttpFoundation\Cookie;

/**
 * Sets a cookie containing the CSRF token
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
    protected $paths;
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
     * @param array                   $paths
     * @param string                  $cookieName
     * @param int                     $cookieExpire
     * @param string                  $cookiePath
     * @param string                  $cookieDomain
     * @param bool                    $cookieSecure
     */
    public function __construct(AngularCsrfTokenManager $angularCsrfTokenManager,
                                array $paths,
                                $cookieName,
                                $cookieExpire,
                                $cookiePath,
                                $cookieDomain,
                                $cookieSecure
    )
    {
        $this->angularCsrfTokenManager = $angularCsrfTokenManager;
        $this->paths = $paths;
        $this->cookieName = $cookieName;
        $this->cookieExpire = $cookieExpire;
        $this->cookiePath = $cookiePath;
        $this->cookieDomain = $cookieDomain;
        $this->cookieSecure = $cookieSecure;
    }

    /**
     * Sets a cookie to the response containing the CRSF token
     *
     * @param FilterResponseEvent $event
     */
    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $pathInfo = $event->getRequest()->getPathInfo();
        $cookie = false;

        foreach ($this->paths as $path) {
            if (preg_match(sprintf('#%s#', $path), $pathInfo)) {
                $cookie = true;
                break;
            }
        }

        if (!$cookie) {
            return;
        }

        $event->getResponse()->headers->setCookie(new Cookie(
            $this->cookieName,
            $this->angularCsrfTokenManager->getToken()->getValue(),
            $this->cookieExpire,
            $this->cookiePath,
            $this->cookieDomain,
            $this->cookieSecure,
            false
        ));
    }
}
