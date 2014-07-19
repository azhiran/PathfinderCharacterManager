<?php
/**
 * Created by PhpStorm.
 * User: jean-gui
 * Date: 06/07/14
 * Time: 19:04
 */

namespace Troulite\PathfinderBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Troulite\PathfinderBundle\Entity\Abilities;
use Troulite\PathfinderBundle\Entity\BaseCharacter;
use Troulite\PathfinderBundle\Entity\CharacterFeat;
use Troulite\PathfinderBundle\Entity\ClassDefinition;
use Troulite\PathfinderBundle\Entity\Skill;

/**
 * Class BaseCharacter
 *
 * @package Troulite\PathfinderBundle\Model
 */
class Character
{
    /**
     * @var BaseCharacter
     */
    private $baseCharacter;

    /**
     * @var AbilitiesBonuses
     */
    public $abilitiesBonuses;

    /**
     * @var AttackBonuses
     */
    private $attackBonuses;

    /**
     * @var DefenseBonuses
     */
    private $defenseBonuses;

    /**
     * @var Bonuses
     */
    private $hpBonuses;

    /**
     * @var array
     */
    private $skillsBonuses;

    /**
     * @var Bonuses
     */
    private $speedBonuses;

    /**
     * @param BaseCharacter $baseCharacter
     */
    public function __construct(BaseCharacter $baseCharacter)
    {
        $this->baseCharacter = $baseCharacter;
        $this->defenseBonuses = new DefenseBonuses();
        $this->attackBonuses = new AttackBonuses();
        $this->hpBonuses = new Bonuses();
        $this->skillsBonuses = array();
        $this->speedBonuses = new Bonuses();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->baseCharacter->__toString();
    }

    /**
     * @param BaseCharacter $baseCharacter
     */
    public function setBaseCharacter($baseCharacter)
    {
        $this->baseCharacter = $baseCharacter;
    }

    /**
     * @return BaseCharacter
     */
    public function getBaseCharacter()
    {
        return $this->baseCharacter;
    }

    /**
     * @return AbilitiesBonuses
     */
    public function getAbilitiesBonuses()
    {
        return $this->abilitiesBonuses;
    }

    /**
     * @param AbilitiesBonuses $abilitiesBonuses
     */
    public function setAbilitiesBonuses($abilitiesBonuses)
    {
        $this->abilitiesBonuses = $abilitiesBonuses;
    }

    /**
     * @param Bonuses $hpBonuses
     *
     * @return $this
     */
    public function setHpBonuses(Bonuses $hpBonuses)
    {
        $this->hpBonuses = $hpBonuses;

        return $this;
    }

    /**
     * @return Bonuses
     */
    public function getHpBonuses()
    {
        return $this->hpBonuses;
    }

    /**
     * @param array $skillsBonuses
     */
    public function setSkillsBonuses($skillsBonuses)
    {
        $this->skillsBonuses = $skillsBonuses;
    }

    /**
     * @return array
     */
    public function &getSkillsBonuses()
    {
        return $this->skillsBonuses;
    }

    /**
     * @param AttackBonuses $attackBonuses
     */
    public function setAttackBonuses($attackBonuses)
    {
        $this->attackBonuses = $attackBonuses;
    }

    /**
     * @return AttackBonuses
     */
    public function getAttackBonuses()
    {
        return $this->attackBonuses;
    }

    /**
     * @param DefenseBonuses $defenseBonuses
     *
     * @return $this
     */
    public function setDefenseBonuses(DefenseBonuses $defenseBonuses)
    {
        $this->defenseBonuses = $defenseBonuses;

        return $this;
    }

    /**
     * @return DefenseBonuses
     */
    public function getDefenseBonuses()
    {
        return $this->defenseBonuses;
    }

    /**
     * @param Skill $skill
     *
     * @return int
     */
    public function getSkillRank(Skill $skill)
    {
        $rank = 0;
        foreach ($this->baseCharacter->getLevels() as $level) {
            foreach ($level->getSkills() as $levelSkill) {
                if ($levelSkill->getSkill() === $skill) {
                    $rank += $levelSkill->getValue();
                    break;
                }
            }
        }

        return $rank;
    }

    /**
     * @param Skill $skill
     *
     * @return bool
     */
    public function hasClassBonus(Skill $skill)
    {
        foreach ($this->baseCharacter->getLevels() as $level) {
            if ($level->getClassDefinition()->getClassSkills()->contains($skill)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array
     */
    public function getRangedAttackRoll()
    {
        return $this->getAttackRoll("ranged", $this->getAbilityModifier($this->getDexterity()));
    }

    /**
     * @return int
     */
    public function getMeleeDamageRoll()
    {
        return $this->attackBonuses->meleeDamage->getBonus() + $this->getAbilityModifier($this->getStrength());
    }

    /**
     * @return int
     */
    public function getRangedDamageRoll()
    {
        return $this->attackBonuses->rangedDamage->getBonus();
    }

    /**
     * Get strength
     *
     * @return integer
     */
    public function getStrength()
    {
        $levelBonus = 0;
        foreach ($this->baseCharacter->getLevels() as $level) {
            if ($level->getExtraAbility() == Abilities::STRENGTH) {
                $levelBonus += 1;
            }
        }

        return
            $this->baseCharacter->getAbilities()->getBaseStrength() +
            $this->baseCharacter->getAbilities()->getBonuses()->strength->getBonus() +
            $levelBonus;
    }

    /**
     * Get dexterity
     *
     * @return integer
     */
    public function getDexterity()
    {
        $levelBonus = 0;
        foreach ($this->baseCharacter->getLevels() as $level) {
            if ($level->getExtraAbility() == Abilities::DEXTERITY) {
                $levelBonus += 1;
            }
        }

        return
            $this->baseCharacter->getAbilities()->getBaseDexterity() +
            $this->baseCharacter->getAbilities()->getBonuses()->dexterity->getBonus() +
            $levelBonus;
    }

    /**
     * Get constitution
     *
     * @return integer
     */
    public function getConstitution()
    {
        $levelBonus = 0;
        foreach ($this->baseCharacter->getLevels() as $level) {
            if ($level->getExtraAbility() == Abilities::CONSTITUTION) {
                $levelBonus += 1;
            }
        }

        return
            $this->baseCharacter->getAbilities()->getBaseConstitution() +
            $this->baseCharacter->getAbilities()->getBonuses()->constitution->getBonus() +
            $levelBonus;
    }

    /**
     * Get intelligence
     *
     * @return integer
     */
    public function getIntelligence()
    {
        $levelBonus = 0;
        foreach ($this->baseCharacter->getLevels() as $level) {
            if ($level->getExtraAbility() == Abilities::INTELLIGENCE) {
                $levelBonus += 1;
            }
        }

        return
            $this->baseCharacter->getAbilities()->getBaseIntelligence() +
            $this->baseCharacter->getAbilities()->getBonuses()->intelligence->getBonus() +
            $levelBonus;
    }

    /**
     * Get wisdom
     *
     * @return integer
     */
    public function getWisdom()
    {
        $levelBonus = 0;
        foreach ($this->baseCharacter->getLevels() as $level) {
            if ($level->getExtraAbility() == Abilities::WISDOM) {
                $levelBonus += 1;
            }
        }

        return
            $this->baseCharacter->getAbilities()->getBaseWisdom() +
            $this->baseCharacter->getAbilities()->getBonuses()->wisdom->getBonus() +
            $levelBonus;
    }

    /**
     * Get charisma
     *
     * @return integer
     */
    public function getCharisma()
    {
        $levelBonus = 0;
        foreach ($this->baseCharacter->getLevels() as $level) {
            if ($level->getExtraAbility() == Abilities::CHARISMA) {
                $levelBonus += 1;
            }
        }

        return
            $this->baseCharacter->getAbilities()->getBaseCharisma() +
            $this->baseCharacter->getAbilities()->getBonuses()->charisma->getBonus() +
            $levelBonus;
    }

    /**
     * @return array
     */
    public function getMeleeAttackRoll()
    {
        return $this->getAttackRoll("melee", $this->getAbilityModifier($this->getStrength()));
    }

    /**
     * @param $type
     * @param $modifier
     *
     * @return array
     */
    private function getAttackRoll($type, $modifier)
    {
        $bab          = $this->getBab();
        $ar           = $bab + $modifier;
        $bonusAttacks = 0;
        $ars          = array();

        switch ($type) {
            case 'ranged':
                $ar += $this->attackBonuses->rangedAttackRolls->getBonus();
                $bonusAttacks = $this->attackBonuses->rangedAttacks->getBonus();
                break;
            case 'melee':
                $ar += $this->attackBonuses->meleeAttackRolls->getBonus();
                $bonusAttacks = $this->attackBonuses->meleeAttacks->getBonus();
        }

        /** @noinspection PhpExpressionResultUnusedInspection */
        for ($bonusAttacks; $bonusAttacks > 0; $bonusAttacks--) {
            $ars[] = $ar;
        }
        /** @noinspection PhpExpressionResultUnusedInspection */
        for ($bab; $bab >= 0; $bab -= 5) {
            $ars[] = $ar;
            $ar -= 5;
        }

        return $ars;
    }

    /**
     * @return int
     */
    public function getBab()
    {
        $bab = 0;
        foreach ($this->getLevelPerClass() as $classLevel) {
            /** @var $class ClassDefinition */
            $class = $classLevel['class'];
            $bab += $class->getBab()[$classLevel['level'] - 1];
        }

        return $bab;
    }

    /**
     * Get this character's maximumhit points
     *
     * @return int
     */
    public function getMaxHp()
    {
        $hp = $this->getHpBonuses()->getBonus();
        foreach ($this->baseCharacter->getLevels() as $level) {
            $hp += $level->getHpRoll() + $this->getAbilityModifier($this->getConstitution());

            // Extra hit point if favored class
            if ($this->baseCharacter->getExtraPoint() === 'hp' && $level->isFavoredClass()) {
                $hp += 1;
            }
        }

        return $hp;
    }

    /**
     * @return array
     */
    public function getLevelPerClass()
    {
        /** @var $levels array */
        $levels = array();
        foreach ($this->baseCharacter->getLevels() as $level) {
            if(array_key_exists($level->getClassDefinition()->getId(), $levels)) {
                $levels[$level->getClassDefinition()->getId()]['level']++;
            } else {
                $levels[$level->getClassDefinition()->getId()] = array(
                    'class' => $level->getClassDefinition(),
                    'level' => 1
                );
            }
        }

        return $levels;
    }

    /**
     * Get the ability modifier corresponding to the value of the argument
     *
     * @param int $value
     *
     * @return int
     */
    public function getAbilityModifier($value)
    {
        return (int)(($value - ($value % 2) - 10) / 2);
    }

    /**
     * Get base reflexes
     *
     * @return int
     */
    public function getBaseReflexes()
    {
        $reflexes = 0;
        foreach ($this->getLevelPerClass() as $classLevel) {
            /** @var $class ClassDefinition */
            $class = $classLevel['class'];
            $reflexes += $class->getReflexes()[$classLevel['level'] - 1];
        }

        return $reflexes;
    }

    /**
     * Get reflexes (sum of base reflexes + dexterity modifier + bonuses
     *
     * @return int
     */
    public function getReflexes()
    {
        return $this->getBaseReflexes()
        + $this->getAbilityModifier($this->getDexterity())
        + $this->getDefenseBonuses()->reflexes->getBonus();
    }

    /**
     * Get base fortitude
     *
     * @return int
     */
    public function getBaseFortitude()
    {
        $fortitude = 0;
        foreach ($this->getLevelPerClass() as $classLevel) {
            /** @var $class ClassDefinition */
            $class = $classLevel['class'];
            $fortitude += $class->getFortitude()[$classLevel['level'] - 1];
        }

        return $fortitude;
    }

    /**
     * Get fortitude (sum of base fortitude + constitution modifier + bonuses
     *
     * @return int
     */
    public function getFortitude()
    {
        return $this->getBaseFortitude()
        + $this->getAbilityModifier($this->getConstitution())
        + $this->getDefenseBonuses()->fortitude->getBonus();
    }

    /**
     * Get base will
     *
     * @return int
     */
    public function getBaseWill()
    {
        $will = 0;
        foreach ($this->getLevelPerClass() as $classLevel) {
            /** @var $class ClassDefinition */
            $class = $classLevel['class'];
            $will += $class->getWill()[$classLevel['level'] - 1];
        }

        return $will;
    }

    /**
     * Get will (sum of base will + wisdom modifier + bonuses
     *
     * @return int
     */
    public function getWill()
    {
        return $this->getBaseWill()
        + $this->getAbilityModifier($this->getWisdom())
        + $this->getDefenseBonuses()->will->getBonus();
    }

    /**
     * Get this character's current level
     *
     * @return int
     */
    public function getLevel()
    {
        return $this->baseCharacter->getLevel();
    }

    /**
     * @param $skill
     * @param $value
     */
    public function setSkillBonus($skill, $value)
    {
        $this->skillsBonuses[$skill] = $value;
    }

    /**
     * @param $skill
     *
     * @return int
     */
    public function getSkillBonus($skill)
    {
        if (array_key_exists($skill, $this->getSkillsBonuses())) {
            return $this->getSkillsBonuses()[$skill];
        }

        return 0;
    }

    /**
     * @param Bonuses $speedBonuses
     */
    public function setSpeedBonuses($speedBonuses)
    {
        $this->speedBonuses = $speedBonuses;
    }

    /**
     * @return Bonuses
     */
    public function getSpeedBonuses()
    {
        return $this->speedBonuses;
    }

    /**
     * Return all feats this character possesses
     * @return ArrayCollection|CharacterFeat[]
     */
    public function getFeats()
    {
        $feats = array();
        foreach($this->getBaseCharacter()->getLevels() as $level) {
            $feats = array_merge($feats, $level->getFeats()->toArray());
        }

        return new ArrayCollection($feats);
    }

    /**
     * @todo dexterity modifier limited by armor
     * @todo bonuses
     * @return int
     */
    public function getAc()
    {
        return
            10 +
            $this->getAbilityModifier($this->getDexterity()) +
            $this->getDefenseBonuses()->ac->getBonus();
    }

    /**
     * @todo bonuses
     * @return int
     */
    public function getTouchAc()
    {
        return 10 + $this->getAbilityModifier($this->getDexterity());
    }

    /**
     * Flat-footed AC doesn't take dexterity or dodge bonus
     * @return int
     */
    public function getFlatFootedAc()
    {
        /** @var $dodgeBonuses Bonus[] */
        $dodgeBonuses = array_filter(
            $this->getDefenseBonuses()->ac->getBonuses(),
            function(Bonus $bonus) {
                return $bonus->getType() === 'dodge';
            }
        );
        $dodgeBonus = 0;
        foreach ($dodgeBonuses as $db) {
            $dodgeBonus += $db->getValue();
        }
        return 10 + $this->getDefenseBonuses()->ac->getBonus() - $dodgeBonus;
    }

    /**
     * @return int
     */
    public function getInitiative()
    {
        return $this->getAbilityModifier($this->getDexterity()) +
            $this->getAttackBonuses()->initiative->getBonus();
    }

    /**
     * @param string $ability
     *
     * @return int
     */
    public function getModifierByAbility($ability)
    {
        switch ($ability) {
            case 'strength':
                return $this->getAbilityModifier($this->getStrength());
            case 'dexterity':
                return $this->getAbilityModifier($this->getDexterity());
            case 'constitution':
                return $this->getAbilityModifier($this->getConstitution());
            case 'intelligence':
                return $this->getAbilityModifier($this->getIntelligence());
            case 'wisdom':
                return $this->getAbilityModifier($this->getWisdom());
            case 'charisma':
                return $this->getAbilityModifier($this->getCharisma());
        }
        return 0;
    }
}