<?php

namespace Troulite\PathfinderBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Troulite\PathfinderBundle\Entity\ClassDefinition;
use Troulite\PathfinderBundle\Entity\ClassSpell;
use Troulite\PathfinderBundle\Entity\SubClass;

/**
 * ClassSpellRepository
 *
 * This class was generated by the PhpStorm "Php Annotations" Plugin. Add your own custom
 * repository methods below.
 */
class ClassSpellRepository extends EntityRepository
{
    /**
     * Find a class spell by name and class
     *
     * @param string $name
     * @param ClassDefinition $class
     * @param SubClass[] $subClasses
     *
     * @return null|ClassSpell
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByNameAndClass($name, ClassDefinition $class, $subClasses = null)
    {
        if ($subClasses && count($subClasses) > 0) {
            $query = 'SELECT cs FROM TroulitePathfinderBundle:ClassSpell cs LEFT JOIN cs.spell s WHERE s.name = :name AND cs.subClass IN (:cid)';

            $res = $this->_em->createQuery($query)->setParameters([
                'name' => $name,
                'cid'  => $subClasses,
            ])->getResult();

            if ($res && count($res) > 0) {
                return $res[0];
            }
        }

        // No spell for subclass, let's try for class

        $query = 'SELECT cs FROM TroulitePathfinderBundle:ClassSpell cs LEFT JOIN cs.spell s WHERE s.name = :name AND cs.class = :cid';

        return $this->_em->createQuery($query)->setParameters([
            'name' => $name,
            'cid'  => $class->getId(),
        ])->getSingleResult();
    }
}