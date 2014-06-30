<?php
/**
 * Created by PhpStorm.
 * User: jean-gui
 * Date: 29/06/14
 * Time: 17:38
 */

namespace Troulite\PathfinderBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Troulite\PathfinderBundle\Entity\User;

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
        $user = $this->get('security.context')->getToken()->getUser();

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirect($this->generateUrl('fos_user_security_login'));
        }

        return array('user' => $user);
    }
} 