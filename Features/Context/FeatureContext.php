<?php

/**
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Features\Context;

use Behat\Behat\Context\SnippetAcceptingContext;
use Dunglas\AngularCsrfBundle\Features\Context\Fixtures\TestKernel;
use Symfony\Component\HttpFoundation\Request;

class FeatureContext implements SnippetAcceptingContext
{
    private $app;
    private $lastResponse;
    private $lastThrowException;

    public function __construct()
    {
        $this->app = new TestKernel();
        $this->app->boot();
    }

    /**
     * @Given I send POST request to :arg1 with valid csrf header
     */
    public function iSendPostRequestToWithValidCsrfHeader($path)
    {
        $request = Request::create($path, 'POST');
        $request->headers->add(array(
            'X-XSRF-TOKEN' => $this->app->getContainer()->get('dunglas_angular_csrf.token_manager')->getToken()->getValue()
        ));
        $this->lastResponse = $this->app->handle($request);
    }

    /**
     * @Given I send POST request to :arg1 with invalid csrf header
     */
    public function iSendPostRequestToWithInvalidCsrfHeader($path)
    {
        $request = Request::create($path, 'POST');
        $request->headers->add(array(
            'X-XSRF-TOKEN' => 'invalid_csrf_token'
        ));

        $this->lastResponse = $this->app->handle($request);
    }

    /**
     * @Then the response code should be :arg1
     */
    public function theResponseShouldCodeBe($code)
    {
        if ($this->lastResponse->getStatusCode() !== (int) $code) {
            throw new \RuntimeException(sprintf('Response code expected %s, but got %s', $code, $this->lastResponse->getStatusCode()));
        }
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($content)
    {
        if ($content !== $this->lastResponse->getContent()) {
            throw new \RuntimeException('Request wasnt ok');
        }
    }
}
