<?php

namespace spec\Dunglas\AngularCsrfBundle\DependencyInjection;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class DunglasAngularCsrfExtensionSpec extends ObjectBehavior
{
    public function it_is_initializable()
    {
        $this->shouldHaveType('Dunglas\AngularCsrfBundle\DependencyInjection\DunglasAngularCsrfExtension');
    }

    public function it_loads(ContainerBuilder $container, ParameterBagInterface $parameterBag)
    {
        $configs = array(
            'dunglas_angular_csrf' => array(
                'token' => array('id' => 'myid'),
                'cookie' => array(
                    'name' => 'cookiename',
                    'expire' => 1312,
                    'path' => '/path',
                    'domain' => 'example.com',
                    'secure' => true,
                    'set_on' => array('/city', '/cities')
                ),
                'header' => array('name' => 'myheader'),
                'secure' => array('/lille', '/houdain', '/bruay')
            )
        );

        $container->getParameterBag()->willReturn($parameterBag);
        $container->hasExtension('http://symfony.com/schema/dic/services')->willReturn(false);
        $container->addResource(Argument::type('Symfony\Component\Config\Resource\ResourceInterface'))->shouldBeCalled();
        $container->setDefinition('dunglas_angular_csrf.token_manager', Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();
        $container->setDefinition('dunglas_angular_csrf.validation_listener', Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();
        $container->setDefinition('dunglas_angular_csrf.cookie_listener', Argument::type('Symfony\Component\DependencyInjection\Definition'))->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.token.id', $configs['dunglas_angular_csrf']['token']['id'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.cookie.name', $configs['dunglas_angular_csrf']['cookie']['name'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.cookie.expire', $configs['dunglas_angular_csrf']['cookie']['expire'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.cookie.path', $configs['dunglas_angular_csrf']['cookie']['path'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.cookie.domain', $configs['dunglas_angular_csrf']['cookie']['domain'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.cookie.secure', $configs['dunglas_angular_csrf']['cookie']['secure'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.cookie.set_on', $configs['dunglas_angular_csrf']['cookie']['set_on'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.header.name', $configs['dunglas_angular_csrf']['header']['name'])->shouldBeCalled();
        $container->setParameter('dunglas_angular_csrf.secure', $configs['dunglas_angular_csrf']['secure'])->shouldBeCalled();

        $this->load($configs, $container);
    }
}
