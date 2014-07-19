<?php

namespace Troulite\PathfinderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Troulite\PathfinderBundle\Entity\Abilities;
use Troulite\PathfinderBundle\Entity\Level;

/**
 * Class LevelUpClassType
 *
 * @package Troulite\PathfinderBundle\Form
 */
class LevelUpClassType extends AbstractType
{
    /**
     * @var
     */
    private $advancement;

    /**
     * @param $advancement
     */
    public function __construct($advancement)
    {
        $this->advancement = $advancement;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('classDefinition')
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var $level Level */
                $level     = $event->getData();
                $character = $level->getCharacter();
                $form      = $event->getForm();

                // Extra ability point
                if (
                    $level &&
                    $character->getLevel() > 0 &&
                    $this->advancement[$character->getLevel()]['ability']
                ) {
                    $form->add(
                        'extraAbility',
                        'choice',
                        array(
                            'choices' => array(
                                Abilities::STRENGTH     => mb_convert_case(Abilities::STRENGTH, MB_CASE_TITLE),
                                Abilities::DEXTERITY    => mb_convert_case(Abilities::DEXTERITY, MB_CASE_TITLE),
                                Abilities::CONSTITUTION => mb_convert_case(Abilities::CONSTITUTION, MB_CASE_TITLE),
                                Abilities::INTELLIGENCE => mb_convert_case(Abilities::INTELLIGENCE, MB_CASE_TITLE),
                                Abilities::WISDOM       => mb_convert_case(Abilities::WISDOM, MB_CASE_TITLE),
                                Abilities::CHARISMA     => mb_convert_case(Abilities::CHARISMA, MB_CASE_TITLE)
                            )
                        )
                    );
                }
            }
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troulite\PathfinderBundle\Entity\Level'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troulite_pathfinderbundle_level';
    }
}