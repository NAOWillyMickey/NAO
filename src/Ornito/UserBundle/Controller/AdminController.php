<?php

namespace Ornito\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AdminController extends Controller
{
    public function indexAction()
    {
      if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux Administrateur.');
      }
    $userManager = $this->get('fos_user.user_manager');
    $usersList = $userManager->findUsers();
    return $this->render('OrnitoUserBundle:Admin:index.html.twig', array('usersList' => $usersList));
  }


}
