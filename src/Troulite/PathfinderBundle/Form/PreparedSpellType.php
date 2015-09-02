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

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Troulite\PathfinderBundle\Entity\Character;
use Troulite\PathfinderBundle\Entity\ClassSpell;
use Troulite\PathfinderBundle\Entity\PreparedSpell;

/**
 * Class PreparedSpellType
 *
 * @package Troulite\PathfinderBundle\Form
 */
class PreparedSpellType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($options) {
                /** @var PreparedSpell $preparedSpell */
                $preparedSpell = $event->getData();
                /** @var Character $character */
                $character     = $options['character'];
                $form          = $event->getForm();
                $groupedSpells = array();
                /** @var $em EntityManager */
                $em = $options['em'];

                for ($i=0; $i <= $options['preparedLevels'][(int)$form->getName()]; $i++) {
                    if ($preparedSpell->getClass()->getKnownSpellsPerLevel() && $i > 0) {
                        $spellsBySpellLevel = $character->getLearnedSpellsBySpellLevel();
                        /** @var ClassSpell[] $spells */
                        foreach ($spellsBySpellLevel as $level => $spells) {
                            foreach ($spells as $classSpell) {
                                $groupedSpells['Level ' . $classSpell->getSpellLevel() . ' spells'][] = $classSpell->getSpell();
                            }
                        }
                    } else {
                        $qb = $em->createQueryBuilder()->select('sp')->from('TroulitePathfinderBundle:Spell', 'sp')
                            ->join('TroulitePathfinderBundle:ClassSpell', 'cs', Join::WITH, 'sp = cs.spell')
                            ->andWhere('cs.class = ?1')
                            ->andWhere('cs.spellLevel = ?2')
                            ->addOrderBy('cs.spellLevel', 'ASC')
                            ->addOrderBy('sp.name', 'ASC');
                        $qb->setParameter(1, $preparedSpell->getClass())
                            ->setParameter(2, $i);

                        /** @var ClassSpell[] $spells */
                        $spells = $qb->getQuery()->execute();
                        if ($spells) {
                            $groupedSpells['Level ' . $i . ' spells'] = $spells;
                        }
                    }
                }

                $form->add(
                    'spell',
                    null,
                    array(
                        'label' => /** @Ignore */ 'Level ' . $options['preparedLevels'][(int)$form->getName()] . ' Spell',
                        'choices' => $groupedSpells,
                    )
                );
            }
        );
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Troulite\PathfinderBundle\Entity\PreparedSpell'
        ));
        $resolver->setRequired(array('em', 'preparedLevels', 'character'));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'troulite_pathfinderbundle_preparedspell';
    }
}
