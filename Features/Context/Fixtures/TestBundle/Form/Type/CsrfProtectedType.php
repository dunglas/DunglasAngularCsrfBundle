<?php

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Features\Context\Fixtures\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Test form with enabled CSRF default protection.
 *
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
class CsrfProtectedType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => false));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '';
    }
}
