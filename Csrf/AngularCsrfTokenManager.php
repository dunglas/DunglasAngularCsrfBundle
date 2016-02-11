<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Csrf;

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * Angular CSRF token manager.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AngularCsrfTokenManager
{
    /**
     * @var CsrfTokenManagerInterface
     */
    protected $csrfTokenManager;
    /**
     * @var string
     */
    protected $tokenId;

    /**
     * @param CsrfTokenManagerInterface $csrfTokenManager The CSRF Token Manager to use
     * @param string                    $tokenId          The CSRF token ID
     */
    public function __construct(CsrfTokenManagerInterface $csrfTokenManager, $tokenId)
    {
        $this->csrfTokenManager = $csrfTokenManager;
        $this->tokenId = $tokenId;
    }

    /**
     * Gets the CSRF token.
     *
     * @return CsrfToken
     *
     * @see CsrfTokenManagerInterface::getToken()
     */
    public function getToken()
    {
        return $this->csrfTokenManager->getToken($this->tokenId);
    }

    /**
     * Refreshes the CSRF token.
     *
     * @return CsrfToken
     *
     * @see CsrfTokenManagerInterface::refreshToken()
     */
    public function refreshToken()
    {
        return $this->csrfTokenManager->refreshToken($this->tokenId);
    }

    /**
     * Removes the CSRF Token.
     *
     * @return string|null
     *
     * @see CsrfTokenManagerInterface::removeToken()
     */
    public function removeToken()
    {
        return $this->csrfTokenManager->removeToken($this->tokenId);
    }

    /**
     * Tests if the given token value is valid.
     *
     * @param $value The CSRF token value to test
     *
     * @return bool
     *
     * @see CsrfTokenManagerInterface::isTokenValid()
     */
    public function isTokenValid($value)
    {
        $csrfToken = new CsrfToken($this->tokenId, $value);

        return $this->csrfTokenManager->isTokenValid($csrfToken);
    }
}
