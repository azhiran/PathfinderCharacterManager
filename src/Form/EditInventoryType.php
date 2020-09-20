<?php

namespace App\Form;

use App\Entity\Characters\Character;
use App\Form\Type\EditInventoryItemType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EditInventoryType
 *
 * @package App\Form
 */
class EditInventoryType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'inventory_items',
                CollectionType::class,
                array(
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'by_reference' => false,
                    'entry_type'         => EditInventoryItemType::class,
                    'label'        => 'items',
                    'entry_options'      => array('label' => false),
                    'widget_add_btn' => array('icon' => 'plus-sign', 'label' => 'add.item'),
                    'widget_remove_btn' => array('icon' => 'trash', 'label' => 'remove.item'),
                    'show_legend' => false
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Character::class
        ));
    }
}
