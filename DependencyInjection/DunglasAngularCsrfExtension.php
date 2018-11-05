<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class DunglasAngularCsrfExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('dunglas_angular_csrf.token.id', $config['token']['id']);
        $container->setParameter('dunglas_angular_csrf.cookie.name', $config['cookie']['name']);
        $container->setParameter('dunglas_angular_csrf.cookie.expire', $config['cookie']['expire']);
        $container->setParameter('dunglas_angular_csrf.cookie.path', $config['cookie']['path']);
        $container->setParameter('dunglas_angular_csrf.cookie.domain', $config['cookie']['domain']);
        $container->setParameter('dunglas_angular_csrf.cookie.secure', $config['cookie']['secure']);
        $container->setParameter('dunglas_angular_csrf.cookie.set_on', $config['cookie']['set_on']);
        $container->setParameter('dunglas_angular_csrf.header.name', $config['header']['name']);
        $container->setParameter('dunglas_angular_csrf.secure', $config['secure']);
        $container->setParameter('dunglas_angular_csrf.exclude', $config['exclude']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}
