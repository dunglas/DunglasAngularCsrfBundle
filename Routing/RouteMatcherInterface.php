<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Routing;

use Symfony\Component\HttpFoundation\Request;

/**
 * Routes checker.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
interface RouteMatcherInterface
{
    /**
     * Checks is the current request match configured routes.
     *
     * @param Request $request
     * @param array   $routes
     *
     * @return bool
     */
    public function match(Request $request, array $routes);
}
