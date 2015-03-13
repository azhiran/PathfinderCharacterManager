<?php
/**
 * Created by PhpStorm.
 * User: jean-gui
 * Date: 06/03/15
 * Time: 14:08
 */

namespace Troulite\PathfinderBundle\Model;

use Troulite\PathfinderBundle\Entity\Spell;

/**
 * Class CastableLevelSpells
 *
 * @package Troulite\PathfinderBundle\Model
 */
class CastableLevelSpells {
    /**
     * @var int
     */
    private $level;

    /**
     * @var Spell[]
     */
    private $spells;

    /**
     * @return int
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * @param int $level
     *
     * @return $this
     */
    public function setLevel($level)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * @return Spell[]
     */
    public function getSpells()
    {
        return $this->spells;
    }

    /**
     * @param Spell[] $spells
     *
     * @return $this
     */
    public function setSpells($spells)
    {
        $this->spells = $spells;

        return $this;
    }

    /**
     * @param Spell $spell
     *
     * @return $this
     */
    public function addSpell(Spell $spell)
    {
        $this->spells[] = $spell;

        return $this;
    }

}