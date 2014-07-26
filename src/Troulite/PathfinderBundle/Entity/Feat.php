<?php

namespace Troulite\PathfinderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Troulite\PathfinderBundle\Entity\Traits\Describable;
use Troulite\PathfinderBundle\Entity\Traits\Power;

/**
 * Feat
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Troulite\PathfinderBundle\Repository\FeatRepository")
 */
class Feat
{
    use Power;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string[]
     *
     * @ORM\Column(type="json_array", nullable=true)
     */
    private $prerequisities;

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
     * @return Feat
     */
    public function setName($name)
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
     * Set prerequisities
     *
     * @param array $prerequisities
     *
     * @return Feat
     */
    public function setPrerequisities($prerequisities)
    {
        $this->prerequisities = $prerequisities;

        return $this;
    }

    /**
     * Get prerequisities
     *
     * @return array
     */
    public function getPrerequisities()
    {
        return $this->prerequisities;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getName();
    }
}
