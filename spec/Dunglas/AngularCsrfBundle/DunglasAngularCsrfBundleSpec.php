<?php

namespace spec\Dunglas\AngularCsrfBundle;

use PhpSpec\ObjectBehavior;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DunglasAngularCsrfBundleSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Dunglas\AngularCsrfBundle\DunglasAngularCsrfBundle');
    }

    public function it_builds(ContainerBuilder $container)
    {
        $this->build($container);
    }
}
