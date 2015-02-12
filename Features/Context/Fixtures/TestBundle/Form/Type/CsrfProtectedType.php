<?php

/**
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Dunglas\AngularCsrfBundle\Features\Context\Fixtures\TestBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Test form with enabled csrf default protection
 *
 * @author Michal Dabrowski <dabrowski@brillante.pl>
 */
class CsrfProtectedType extends AbstractType
{
    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', 'text', array('required' => false))
        ;
    }

    /**
     * @see \Symfony\Component\Form\FormTypeInterface
     *
     * @return string
     */
    public function getName()
    {
        return '';
    }
}
