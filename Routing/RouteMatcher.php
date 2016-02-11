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
 * Routes matcher default implementation.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class RouteMatcher implements RouteMatcherInterface
{
    /**
     * {@inheritdoc}
     */
    public function match(Request $request, array $routes)
    {
        foreach ($routes as $route) {
            if (empty($route['methods'])) {
                $methodMatch = true;
            } else {
                $methodMatch = false;
                foreach ($route['methods'] as $method) {
                    if (strtoupper($method) === $request->getMethod()) {
                        $methodMatch = true;
                        break;
                    }
                }
            }

            if (
                $methodMatch
                &&
                (empty($route['path']) || preg_match(sprintf('#%s#', $route['path']), $request->getPathInfo()))
                &&
                (empty($route['route']) || preg_match(sprintf('#%s#', $route['route']), $request->get('_route')))
                &&
                (empty($route['host']) || preg_match(sprintf('#%s#', $route['host']), $request->getHost()))
            ) {
                return true;
            }
        }

        return false;
    }
}
