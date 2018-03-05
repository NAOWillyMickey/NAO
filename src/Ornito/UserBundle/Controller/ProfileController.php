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

class ProfileController extends Controller
{

  /**
   * Edit the user.
   *
   * @param Request $request
   *
   * @return Response
   */
  public function editAction(Request $request)
  {
      $user = $this->getUser();
      if (!is_object($user) || !$user instanceof UserInterface) {
          throw new AccessDeniedException('This user does not have access to this section.');
      }


      return $this->render('@FOSUser/Profile/profile_edit.html.twig'
      );
  }


}
