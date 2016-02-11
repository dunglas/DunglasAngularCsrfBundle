<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Form\Extension;

use Dunglas\AngularCsrfBundle\Csrf\AngularCsrfTokenManager;
use Dunglas\AngularCsrfBundle\Routing\RouteMatcherInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Form extension that disables the given forms' CSRF token validation
 * in favor of the validation token sent with header.
 * It disables only when header token is valid.
 *
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class DisableCsrfExtension extends AbstractTypeExtension
{
    /**
     * @var AngularCsrfTokenManager
     */
    protected $angularCsrfTokenManager;
    /**
     * @var RouteMatcherInterface
     */
    protected $routeMatcher;
    /**
     * @var array
     */
    protected $routes;
    /**
     * @var string
     */
    protected $headerName;

    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    protected $requestStack;

    /**
     * @param AngularCsrfTokenManager $angularCsrfTokenManager
     * @param RouteMatcherInterface   $routeMatcher
     * @param RequestStack            $requestStack
     * @param array                   $routes
     * @param string                  $headerName
     */
    public function __construct(
        AngularCsrfTokenManager $angularCsrfTokenManager,
        RouteMatcherInterface $routeMatcher,
        RequestStack $requestStack,
        array $routes,
        $headerName
    ) {
        $this->angularCsrfTokenManager = $angularCsrfTokenManager;
        $this->routeMatcher = $routeMatcher;
        $this->routes = $routes;
        $this->headerName = $headerName;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }

        if (false === $this->routeMatcher->match($request, $this->routes)) {
            return;
        }

        $value = $request->headers->get($this->headerName);

        if ($this->angularCsrfTokenManager->isTokenValid($value)) {
            $resolver->setDefaults(array(
                'csrf_protection' => false,
            ));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'Symfony\Component\Form\Extension\Core\Type\FormType';
    }
}
