<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Features\Context\Fixtures;

use Dunglas\AngularCsrfBundle\DunglasAngularCsrfBundle;
use Dunglas\AngularCsrfBundle\Features\Context\Fixtures\TestBundle\TestBundle;
use Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Kernel;

/**
 * Test Kernel.
 *
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class TestKernel extends Kernel
{
    private $config;

    public function __construct($config = 'default.yml')
    {
        $this->name = 'app'.uniqid();
        parent::__construct('test', true);

        $fs = new Filesystem();
        if (!$fs->isAbsolutePath($config)) {
            $config = __DIR__.'/config/'.$config;
        }

        if (!file_exists($config)) {
            throw new \RuntimeException(sprintf('The config file "%s" does not exist.', $config));
        }

        $this->config = $config;
    }

    public function registerBundles()
    {
        return array(
            new FrameworkBundle(),
            new SensioFrameworkExtraBundle(),
            new TestBundle(),
            new DunglasAngularCsrfBundle(),
            new TwigBundle(),
        );
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->config);
    }

    public function getCacheDir()
    {
        return sys_get_temp_dir().'/DunglasAngularCsrfBundle/cache/'.$this->name.$this->environment;
    }

    public function getLogDir()
    {
        return sys_get_temp_dir().'/DunglasAngularCsrfBundle/logs';
    }
}
