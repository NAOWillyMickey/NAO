<?php

namespace Ornito\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ornito\UserBundle\Services\UsersManager;
use Ornito\UserBundle\Entity\User;
use Symfony\Component\Translation\TranslatorInterface;

class AdminController extends Controller
{
    public function indexAction()
    {
      if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
      // Sinon on déclenche une exception « Accès interdit »
      throw new AccessDeniedException('Accès limité aux Administrateur.');
      }
    $FOSuserManager = $this->get('fos_user.user_manager');
    $usersList = $FOSuserManager->findUsers();
    return $this->render('OrnitoUserBundle:Admin:index.html.twig', array('usersList' => $usersList));
  }

  public function CertificationIndexAction()
  {
    if (!$this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
    // Sinon on déclenche une exception « Accès interdit »
    throw new AccessDeniedException('Accès limité aux Administrateur.');
    }
    $FOSuserManager = $this->get('fos_user.user_manager');
    $usersList = $FOSuserManager->findUsers();
    return $this->render('OrnitoUserBundle:Admin:certification_index.html.twig', array('usersList' => $usersList));
  }

  public function NaturalistPromoteValidationAction($userId)
  {
    $FOSuserManager = $this->get('fos_user.user_manager');
    $usersManager = $this->get('ornito_users_manager');
    $user = $FOSuserManager->finduserBy(array('id' => $userId));
    $usersManager->promoteAsNaturalist($user);
    $translatedMessage = $this->get('translator')->trans('Promotion Compte Naturaliste transmise');
    $this->addFlash(
          'success',
          $translatedMessage
    );
    return $this->redirectToRoute('ornito_user_admin_certification_list');
  }

  public function NaturalistPromoteDeclinationAction($userId)
  {
    $FOSuserManager = $this->get('fos_user.user_manager');
    $usersManager = $this->get('ornito_users_manager');
    $user = $FOSuserManager->finduserBy(array('id' => $userId));
    $usersManager->declineAsNaturalist($user);
    $translatedMessage = $this->get('translator')->trans('Le rejet de la demande à bien été transmis');
    $this->addFlash(
          'danger',
          $translatedMessage
    );
    return $this->redirectToRoute('ornito_user_admin_certification_list');
  }

  public function deleteUserAction($userId)
  {
    $FOSuserManager = $this->get('fos_user.user_manager');
    $usersManager = $this->get('ornito_users_manager');
    $user = $FOSuserManager->finduserBy(array('id' => $userId));
    $usersManager->deleteUser($user);
    $translatedMessage = $this->get('translator')->trans('Utlisateur supprimé avec succès');
    $this->addFlash(
          'danger',
          $translatedMessage
    );
    return $this->redirectToRoute('ornito_user_admin_list');
  }
}
