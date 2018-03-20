<?php

namespace Ornito\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Form\Factory\FactoryInterface;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ornito\UserBundle\Services\UsersManager;
use Symfony\Component\Translation\TranslatorInterface;

class ProfileController extends Controller
{

  public function showUserProfileAction($username, $userId)
  {
    $FOSuserManager = $this->get('fos_user.user_manager');
    $user = $FOSuserManager->finduserBy(array('id' => $userId));
    $em = $this->getDoctrine()->getManager();
    $naturalistObsList = $em->getRepository('OrnitoObservationBundle:Watching')->findNaturalistObsList($userId);
    return $this->render('OrnitoUserBundle:Profile:show.html.twig', array(
      'user' => $user,
      'naturalistObsList' => $naturalistObsList,
    ));
  }

  /**
   *  Ask to be Promote  as Naturalist.
   *
   * @param Request $request
   *
   * @return Redirection
   */
  public function NaturalistPromoteRequestAction($userId)
  {
    $user = $this->getUser();
    $user_id = $user->getId();
    if ( (null === $user) | ($user_id != $userId) ) {
      // Ici, l'utilisateur est anonyme ou l'URL n'est pas derrière un pare-feu
      throw new AccessDeniedException('Accès Refusé. Confirmation Identité Requise');
    } else {
      // Ici, $user est une instance de notre classe User
      $usersManager = $this->get('ornito_users_manager');
      $usersManager->promoteNaturalistRequestPending($user);

      $translatedMessage = $this->get('translator')->trans('Votre demande à bien été transmise. Nous vous remercions et la traitons au plus tôt');
      $this->addFlash(
            'success',
            $translatedMessage
      );

      return $this->redirectToRoute('fos_user_profile_show');
    }


  }


}
