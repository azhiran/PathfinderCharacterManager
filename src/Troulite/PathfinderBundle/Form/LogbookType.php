<?php

/*
 * Copyright 2015 Jean-Guilhem Rouel
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Troulite\PathfinderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class LogbookType
 *
 * @package Troulite\PathfinderBundle\Form
 */
class LogbookType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'entries',
                'collection',
                array(
                    'type'                           => new LogbookEntryType(),
                    'allow_add'                      => true,
                    'allow_delete'                   => false,
                    'label_render'                   => false, // not good, prevents rendering add button
                    'options'                        => array(
                        'label_render'                   => false,
                        'horizontal_input_wrapper_class' => false,
                        'horizontal_label_class'         => false,
                        'horizontal_label_offset_class'  => false
                    ),
                    'horizontal_input_wrapper_class' => false,
                    'horizontal_label_class'         => false,
                    'horizontal_label_offset_class'  => false,
                    'by_reference'                   => false,
                )
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Troulite\PathfinderBundle\Entity\Logbook'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troulite_pathfinderbundle_logbook';
    }
}
