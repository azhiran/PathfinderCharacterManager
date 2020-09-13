<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Item
 *
 * @ORM\Table()
 * @ORM\Entity
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
 * @ORM\DiscriminatorMap({
 *     "weapon" = "Weapon", "armor" = "Armor", "shield" = "Shield", "shoulders" = "Shoulders", "ring" = "Ring",
 *     "neck" = "Neck", "belt" = "Belt", "wrists" = "Wrists", "feet" = "Feet", "hands" = "Hands", "eyes" = "Eyes",
 *     "head" = "Head", "headband" = "Headband", "body" = "Body", "chest" = "Chest", "item" = "Item"
 * })
 * @ORM\Cache()
 */
class Item
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="cost", type="integer")
     */
    private $cost;

    /**
     * @var double
     *
     * @ORM\Column(name="weight", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $weight;

    /**
     * @var Collection|ItemPower[]
     *
     * @ORM\ManyToMany(targetEntity="ItemPower", cascade={"all"})
     * @ORM\JoinTable(name="ItemPowers",
     *     joinColumns={@ORM\JoinColumn(name="item_id", referencedColumnName="id")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="power_id", referencedColumnName="id")}
     * )
     * @ORM\Cache()
     */
    private $powers;

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
     * Set name
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set cost
     *
     * @param integer $cost
     *
     * @return $this
     */
    public function setCost(int $cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return integer
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set weight
     *
     * @param double $weight
     *
     * @return $this
     */
    public function setWeight(float $weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return double
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Add power
     *
     * @param ItemPower $power
     *
     * @return $this
     */
    public function addPower(ItemPower $power)
    {
        $this->powers[] = $power;

        return $this;
    }

    /**
     * Remove power
     *
     * @param ItemPower $power
     */
    public function removePower(ItemPower $power)
    {
        $this->powers->removeElement($power);
    }

    /**
     * Get powers
     *
     * @return Collection|ItemPower[]
     */
    public function getPowers()
    {
        return $this->powers;
    }
}