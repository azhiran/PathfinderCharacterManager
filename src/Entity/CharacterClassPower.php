<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * CharacterClassPower
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CharacterClassPower
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Level
     *
     * @ORM\ManyToOne(targetEntity="Level", inversedBy="classPowers")
     * @ORM\JoinColumn(name="level", referencedColumnName="id")
     * @Assert\NotBlank()
     */
    private $level;

    /**
     * @var ClassPower
     *
     * @ORM\ManyToOne(targetEntity="ClassPower")
     * @ORM\JoinColumn(name="class_power", referencedColumnName="id")
     * @ORM\Cache()
     * @Assert\NotBlank()
     */
    private $classPower;

    /**
     * @var boolean
     *
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $extraInformation;

    /**
     * @var ClassPower
     *
     * @ORM\ManyToOne(targetEntity="ClassPower")
     * @ORM\JoinColumn(name="child_power", referencedColumnName="id", nullable=true)
     * @ORM\Cache()
     */
    private $childPower;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set level
     *
     * @param Level $level
     *
     * @return $this
     */
    public function setLevel(Level $level = null)
    {
        $this->level = $level;

        return $this;
    }

    /**
     * Get level
     *
     * @return Level
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Get character
     *
     * @return Character
     */
    public function getCharacter()
    {
        return $this->level->getCharacter();
    }

    /**
     * Set class power
     *
     * @param ClassPower $classPower
     *
     * @return $this
     */
    public function setClassPower(ClassPower $classPower = null)
    {
        $this->classPower = $classPower;

        return $this;
    }

    /**
     * Get class power
     *
     * @return ClassPower
     */
    public function getClassPower()
    {
        if ($this->getChildPower()) {
            return $this->childPower;
        }
        return $this->classPower;
    }

    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return $this
     */
    public function setActive(bool $active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @param $extraInformation
     *
     * @return $this
     */
    public function setExtraInformation($extraInformation)
    {
        $this->extraInformation = $extraInformation;

        return $this;
    }

    /**
     * @return string
     */
    public function getExtraInformation()
    {
        return $this->extraInformation;
    }

    /**
     * @return ClassPower
     */
    public function getChildPower()
    {
        return $this->childPower;
    }

    /**
     * @param ClassPower $childPower
     *
     * @return $this
     */
    public function setChildPower(ClassPower $childPower)
    {
        $this->childPower = $childPower;

        return $this;
    }



    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getClassPower()->__toString();
    }
}
