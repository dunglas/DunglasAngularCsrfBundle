<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Features\Context\Fixtures\TestBundle\Controller;

use Dunglas\AngularCsrfBundle\Features\Context\Fixtures\TestBundle\Form\Type\CsrfProtectedType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Test controller used in scenarios.
 *
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class TestController extends Controller
{
    public function createAction()
    {
        return new Response('Success', 201);
    }

    public function csrfProtectedAction(Request $request)
    {
        $form = $this->createForm(new CsrfProtectedType());
        $form->submit($request->request->all());

        if ($form->isValid()) {
            return new Response('Success', 200);
        }

        return new Response('Success', 400);
    }
}
