<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Removes csrf listeners when token manager isn't loaded.
 *
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class CsrfCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('security.csrf.token_manager')) {
            $container->removeDefinition('dunglas_angular_csrf.validation_listener');
            $container->removeDefinition('dunglas_angular_csrf.form.extension.disable_csrf');
            $container->removeDefinition('dunglas_angular_csrf.cookie_listener');
            $container->removeDefinition('dunglas_angular_csrf.token_manager');
        }
    }
}
