<?php

namespace Ornito\UserBundle\Services;

use Doctrine\ORM\EntityManagerInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Config\Definition\Exception\Exception;
use Ornito\UserBundle\Entity\User;

class UsersManager
{

  protected $em;
  protected $userManager;

  public function __construct(EntityManagerInterface $entityManager, UserManagerInterface $userManager)
    {
      $this->em = $entityManager;
      $this->userManager = $userManager;
    }

  public function createUser()
  {
    $user = new User();
    return $user;
  }

  public function promoteNaturalistRequestPending(User $user)
  {
    $user->setNaturalistPromote('pending');
    $this->userManager->updateUser($user);
  }
  public function declineAsNaturalist(User $user)
  {
    $user->setNaturalistPromote('declined');
    $this->userManager->updateUser($user);
  }

  public function promoteAsNaturalist(User $user)
  {
    $user->addRole('ROLE_NATURALIST');
    $user->setNaturalistPromote('validate');
    $this->userManager->updateUser($user);
  }

  public function removeAsMember(User $user)
  {
    $user->removeRole('ROLE_NATURALIST');
    $this->userManager->updateUser($user);
  }

  public function promoteAsAdmin(User $user)
  {
    $user->addRole('ROLE_ADMIN');
    $this->userManager->updateUser($user);
  }

  public function removeAdminRole(User $user)
  {
    $user->removeRole('ROLE_ADMIN');
    $this->userManager->updateUser($user);
  }

  public function deleteUser(User $user)
  {
    $this->em->remove($user);
    $this->em->flush();
  }

}
