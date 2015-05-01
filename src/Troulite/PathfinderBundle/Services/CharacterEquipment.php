<?php
/**
 * Created by PhpStorm.
 * User: jean-gui
 * Date: 06/07/14
 * Time: 16:53
 */
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

namespace Troulite\PathfinderBundle\Services;

use Troulite\PathfinderBundle\Entity\Armor;
use Troulite\PathfinderBundle\Entity\Belt;
use Troulite\PathfinderBundle\Entity\Body;
use Troulite\PathfinderBundle\Entity\Character;
use Troulite\PathfinderBundle\Entity\Chest;
use Troulite\PathfinderBundle\Entity\Eyes;
use Troulite\PathfinderBundle\Entity\Feet;
use Troulite\PathfinderBundle\Entity\Hands;
use Troulite\PathfinderBundle\Entity\Head;
use Troulite\PathfinderBundle\Entity\Headband;
use Troulite\PathfinderBundle\Entity\Item;
use Troulite\PathfinderBundle\Entity\Neck;
use Troulite\PathfinderBundle\Entity\Ring;
use Troulite\PathfinderBundle\Entity\Shield;
use Troulite\PathfinderBundle\Entity\Shoulders;
use Troulite\PathfinderBundle\Entity\Weapon;
use Troulite\PathfinderBundle\Entity\Wrists;

/**
 * Class CharacterEquipment
 *
 * @package Troulite\PathfinderBundle\Services
 */
class CharacterEquipment
{

    /**
     * @param Character $character
     * @param Item $item
     *
     * @return $this
     * @throws \Exception
     */
    public function equip(Character $character, Item $item)
    {
        $equipment = $character->getEquipment();

        if ($item instanceof Weapon) {
            // Equip weapon in main hand if free or offhand wielding a shield
            if (!$equipment->getMainWeapon() || $equipment->getOffhandWeapon() instanceof Shield) {
                $equipment->setMainWeapon($item);
            } else {
                $equipment->setOffhandWeapon($item);
            }
        } elseif ($item instanceof Armor) {
            $equipment->setArmor($item);
        } elseif ($item instanceof Shield) {
            $equipment->setOffhandWeapon($item);
        } elseif ($item instanceof Shoulders) {
            $equipment->setShoulders($item);
        } elseif ($item instanceof Ring) {
            if ($equipment->getRightFinger()) {
                $equipment->setLeftFinger($item);
            } else {
                $equipment->setRightFinger($item);
            }
        } elseif ($item instanceof Neck) {
            $equipment->setNeck($item);
        } elseif ($item instanceof Belt) {
            $equipment->setBelt($item);
        } elseif ($item instanceof Wrists) {
            $equipment->setWrists($item);
        } elseif ($item instanceof Feet) {
            $equipment->setFeet($item);
        } elseif ($item instanceof Hands) {
            $equipment->setHands($item);
        } elseif ($item instanceof Eyes) {
            $equipment->setEyes($item);
        } elseif ($item instanceof Head) {
            $equipment->setHead($item);
        } elseif ($item instanceof Headband) {
            $equipment->setHeadband($item);
        } elseif ($item instanceof Body) {
            $equipment->setBody($item);
        } elseif ($item instanceof Chest) {
            $equipment->setChest($item);
        } else {
            throw new \Exception('Cannot equip a non-wearable item');
        }

        $character->removeInventory($item, 1);

        return $equipment;
    }

    /**
     * @param Character $character
     * @param $slot
     *
     * @return Character
     */
    public function unequipSlot(Character $character, $slot)
    {
        $equipment = $character->getEquipment();
        $item = null;
        switch($slot) {
            case 'headband':
                $equipment->setHeadband();
                break;
            case 'head':
                $equipment->setHead();
                break;
            case 'eyes':
                $equipment->setEyes();
                break;
            case 'neck':
                $equipment->setNeck();
                break;
            case 'shoulders':
                $equipment->setShoulders();
                break;
            case 'armor':
                $equipment->setArmor();
                break;
            case 'body':
                $equipment->setBody();
                break;
            case 'chest':
                $equipment->setChest();
                break;
            case 'belt':
                $equipment->setBelt();
                break;
            case 'mainWeapon':
                $equipment->setMainWeapon();
                break;
            case 'offhandWeapon':
                $equipment->setOffhandWeapon();
                break;
            case 'wrists':
                $equipment->setWrists();
                break;
            case 'hands':
                $equipment->setHands();
                break;
            case 'rightFinger':
                $equipment->setRightFinger();
                break;
            case 'leftFinger':
                $equipment->setLeftFinger();
                break;
            case 'feet':
                $equipment->setFeet();
                break;
        }

        return $character;
    }
} 