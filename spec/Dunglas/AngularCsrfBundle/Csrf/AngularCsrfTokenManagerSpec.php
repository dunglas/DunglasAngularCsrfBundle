<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace spec\Dunglas\AngularCsrfBundle\Csrf;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

/**
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class AngularCsrfTokenManagerSpec extends ObjectBehavior
{
    const ID = 'spec';
    const VALUE = 'val';

    public function let(CsrfTokenManagerInterface $tokenManager, CsrfToken $token)
    {
        $tokenManager->getToken(self::ID)->willReturn($token);
        $tokenManager->refreshToken(self::ID)->willReturn($token);
        $tokenManager->removeToken(self::ID)->willReturn(self::VALUE);
        $tokenManager->isTokenValid(Argument::type('Symfony\Component\Security\Csrf\CsrfToken'))->willReturn(true);

        $this->beConstructedWith($tokenManager, self::ID);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType('Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager');
    }

    public function it_gets_token()
    {
        $this->getToken()->shouldHaveType('Symfony\Component\Security\Csrf\CsrfToken');
    }

    public function it_refreshes_token()
    {
        $this->refreshToken()->shouldHaveType('Symfony\Component\Security\Csrf\CsrfToken');
    }

    public function it_removes_token()
    {
        $this->removeToken()->shouldReturn(self::VALUE);
    }

    public function it_valids_token()
    {
        $this->isTokenValid(self::VALUE)->shouldReturn(true);
    }
}
