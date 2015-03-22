<?php

namespace Troulite\PathfinderBundle\Controller;

use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Troulite\PathfinderBundle\Entity\Character;
use Troulite\PathfinderBundle\Entity\CharacterClassPower;
use Troulite\PathfinderBundle\Entity\CharacterFeat;
use Troulite\PathfinderBundle\Entity\Level;
use Troulite\PathfinderBundle\Form\EditLevelType;
use Troulite\PathfinderBundle\Form\LevelUpFlow;

/**
 * Level controller.
 *
 * @Route("/characters")
 */
class LevelController extends Controller
{

    /**
     * Edits an existing Character entity.
     *
     * @Route("/{id}/levelup", name="characters_levelup")
     * @Template()
     *
     * @param Character $character
     *
     * @return array|RedirectResponse
     */
    public function levelUpAction(Character $character)
    {
        $level = new Level();
        $level->setValue($character->getLevel() + 1);
        $level->setClassDefinition($character->getFavoredClass());
        $character->addLevel($level);

        /** @var $flow LevelUpFlow */
        $flow = $this->get('troulite_pathfinder.form.flow.levelup');
        $flow->bind($level);

        // Add class powers if they were not already added through a form
        if ($level->getClassDefinition()) {
            foreach ($level->getClassDefinition()->getPowers($character->getLevel($level->getClassDefinition())) as $power) {
                $alreadyAdded = false;
                foreach ($level->getClassPowers() as $classPower) {
                    if ($classPower->getClassPower() === $power) {
                        $alreadyAdded = true;
                        break;
                    }
                }
                if (!$alreadyAdded) {
                    $level->addClassPower((new CharacterClassPower())->setClassPower($power));
                }
            }
        }

        // Cleanup empty feats that may have been added by the form
        foreach ($level->getFeats() as $feat) {
            if ($feat === null || $feat->getFeat() === null) {
                $level->removeFeat($feat);
            }
        }

        // form of the current step
        $form = $flow->createForm();
        if ($flow->isValid($form)) {
            $flow->saveCurrentStepData($form);

            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                // flow finished

                // Cleanup empty skills as well
                foreach ($level->getSkills() as $levelSkill) {
                    if ($levelSkill->getValue() === 0) {
                        $level->removeSkill($levelSkill);
                    }
                }

                // Max HP for first level
                if ($character->getLevel() === 1) {
                    $character->getLevels()[0]->setHpRoll($character->getLevels()[0]->getClassDefinition()->getHpDice());
                }

                /** @var $em EntityManager */
                $em = $this->getDoctrine()->getManager();

                // Add fixed extra feats granted by this level
                foreach ($level->getClassPowers() as $power) {
                    $effects = $power->getClassPower()->getEffects();
                    if (
                        $power->getClassPower()->hasEffects() &&
                        array_key_exists('feat', $effects) &&
                        $effects['feat']['type'] !== 'oneof'
                    ) {
                        $feat = $em->getRepository('TroulitePathfinderBundle:Feat')
                            ->findOneBy(array('name' => $effects['feat']['value']));
                        if ($feat) {
                            $level->addFeat((new CharacterFeat())->setFeat($feat));
                        }
                    }
                }

                $em->persist($level);
                $em->flush();

                $this->get('session')->getFlashBag()->add(
                    'success',
                    $character . ' is now level ' . $character->getLevel()
                );

                return $this->redirect($this->generateUrl('characters_show', array('id' => $character->getId())));
            }
        }

        return array(
            'form'   => $form->createView(),
            'flow'   => $flow,
            'entity' => $character
        );
    }

    /**
     * Edits an existing Character entity.
     *
     * @Route("/{character}/levels/{level}/edit", name="characters_levels_edit")
     * @ParamConverter("level", options={"mapping": {"character" = "character", "level" = "value"}})
     * @Template()
     *
     * @param Level $level
     * @param Request $request
     *
     * @return array|RedirectResponse
     */
    public function editLevelAction(Level $level, Request $request)
    {
        $character = $level->getCharacter();

        $em = $this->getDoctrine()->getManager();

        $form = $this->createForm(
            new EditLevelType(
                $this->container->getParameter('character_advancement'),
                $em,
                $this->get('doctrine'),
                $this->get('property_accessor')
            ),
            $level,
            array(
                'action' => $this->generateUrl(
                    'characters_levels_edit',
                    array('character' => $character->getId(), 'level' => $level->getId())),
                'method' => 'PUT',
            )
        );
        $form->add('submit', 'submit', array('label' => 'Update'));

        $form->handleRequest($request);
        if ($form->isValid()) {
            $em->flush();
        }

        return array(
            'form'   => $form->createView(),
            'entity' => $character,
            'level'  => $level
        );
    }
}
