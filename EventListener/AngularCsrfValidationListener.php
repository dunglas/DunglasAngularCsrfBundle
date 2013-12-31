<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\EventListener;

use Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

/**
 * Checks the validity of the CSRF token sent by AngularJS
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AngularCsrfValidationListener
{
    /**
     * @var AngularCsrfTokenManager
     */
    protected $angularCsrfTokenManager;
    /**
     * @var string[]
     */
    protected $paths;
    /**
     * @var string
     */
    protected $headerName;

    /**
     * @param AngularCsrfTokenManager $angularCsrfTokenManager
     * @param string[]                $paths
     * @param string                  $headerName
     */
    public function __construct(AngularCsrfTokenManager $angularCsrfTokenManager, array $paths, $headerName)
    {
        $this->angularCsrfTokenManager = $angularCsrfTokenManager;
        $this->paths = $paths;
        $this->headerName = $headerName;
    }

    /**
     * Handles CSRF token validation
     *
     * @param  GetResponseEvent          $event
     * @throws AccessDeniedHttpException
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
            return;
        }

        $pathInfo = $event->getRequest()->getPathInfo();
        $secured = false;

        foreach ($this->paths as $path) {
            if (preg_match(sprintf('#%s#', $path), $pathInfo)) {
                $secured = true;
                break;
            }
        }

        if (!$secured) {
            return;
        }

        $value = $event->getRequest()->headers->get($this->headerName);
        if (!$value || !$this->angularCsrfTokenManager->isTokenValid($value)) {
            throw new AccessDeniedHttpException('Bad CSRF token.');
        }
    }
}
