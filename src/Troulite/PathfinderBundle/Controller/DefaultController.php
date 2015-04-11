<?php
/**
 * Created by PhpStorm.
 * User: jean-gui
 * Date: 29/06/14
 * Time: 17:38
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

namespace Troulite\PathfinderBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Troulite\PathfinderBundle\Entity\User;

/**
 * Class DefaultController
 *
 * @package Troulite\PathfinderBundle\Controller
 */
class DefaultController extends Controller
{
    /**
     *
     * @Route("/", name="index")
     * @Template()
     */
    public function indexAction()
    {
        /** @var $user User */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        return array('user' => $user);
    }

    /**
     *
     * @Route("/character-advancement", name="character_advancement")
     * @Template()
     */
    public function characterAdvancementAction()
    {
        return array('advancement' => $this->container->getParameter('character_advancement'));
    }
} 