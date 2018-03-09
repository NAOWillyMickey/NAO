<?php

namespace Ornito\ObservationBundle\Controller;

use Ornito\ObservationBundle\Entity\Watching;
use Ornito\ObservationBundle\Form\WatchingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ObservationController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Timeline Actu "'.$page.'" indisponible pour le moment.');
        }

        $nbPerPage = $this->container->getParameter('nb_per_page');
        $listWatching = $this->getDoctrine()
            ->getManager()
            ->getRepository('OrnitoObservationBundle:Watching')
            ->getObs($page, $nbPerPage)
        ;

        $nbPages = ceil(count($listWatching) / $nbPerPage);

        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('OrnitoObservationBundle:Observation:index.html.twig', array(
            'listWatching' => $listWatching,
            'page' => $page,
            'nbPages' => $nbPages,
        ));
    }

    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $watching = $em->getRepository('OrnitoObservationBundle:Watching')->find($id);

        if ($watching === null) {
            throw new NotFoundHttpException('L\'observation d\'id ' .$id. ' n\'existe pas!');
        }

        return $this->render('OrnitoObservationBundle:Observation:view.html.twig', array(
            'watch' => $watching,

        ));
    }

    public function addAction(Request $request)
    {
        $watching = new Watching($this->getUser());
        $form = $this->createForm(WatchingType::class, $watching);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($watching);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Annonce bien enregistrée.');

            return $this->redirectToRoute('ornito_observation_view', array(
                'id' => $watching->getId(),
            ));

        } else {

            $repository = $this->getDoctrine()->getManager()->getRepository('OrnitoTaxrefBundle:Species');
            $list = $repository->getVernList();
            $vernList = $this->bindVernWithId($list);

            return $this->render('OrnitoObservationBundle:Observation:add.html.twig', array(
                'form' => $form->createView(),
                'vernList' => $vernList,
            ));
        }
    }

    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('OrnitoObservationBundle:Watching');

        $watching = $repository->find($id);

        $watchingUserId = $watching->getUser()->getId();
        $authorization = $this->canAmendOrDelete($watchingUserId);

        if ($authorization === true) {

        if ($watching === null) {
            throw new NotFoundHttpException('L\'observation d\'id ' .$id. ' n\'existe pas!');
        }

        $form = $this->createForm(WatchingType::class, $watching);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em->flush();

            $request->getSession()->getFlashBag()->add('success' , 'Annonce bien modifiée.');

            return $this->redirectToRoute('ornito_observation_view', array(
                'id' => $watching->getId(),
            ));
        }

        $repository = $this->getDoctrine()->getManager()->getRepository('OrnitoTaxrefBundle:Species');
        $list = $repository->getVernList();
        $vernList = $this->bindVernWithId($list);

        return $this->render('OrnitoObservationBundle:Observation:edit.html.twig', array(
            'form' => $form->createView(),
            'watching' => $watching,
            'vernList' => $vernList,
        ));
        } else {
            $request->getSession()->getFlashBag()->add('warning', 'Vous n\'avez pas les droits nécessaires pour modifier cette annonce!');
            return $this->redirectToRoute('fos_user_profile_show');
        }
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $watching = $em->getRepository('OrnitoObservationBundle:Watching')->find($id);

        if ($watching === null) {
            throw new NotFoundHttpException('L\'observation d\'id ' . $id . ' n\'existe pas!');
        }

        $watchingUserId = $watching->getUser()->getId();
        $authorization = $this->canAmendOrDelete($watchingUserId);

        // Available delete observation by the user who add the obs only or by super admin
        if ($authorization === true) {
            $em->remove($watching);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'L\'observation a bien été supprimée.');
            return $this->redirectToRoute('fos_user_profile_show');
        }
        $request->getSession()->getFlashBag()->add('warning', 'Vous n\'avez pas les droits nécessaires pour supprimer cette annonce!');
        return $this->redirectToRoute('fos_user_profile_show');
    }

    /**
     * This return true if current user is the same who ask for action
     * This return true for SUPER_ADMIN_ROLE too
     * @param $watchingUserId
     * @return bool
     */
    private function canAmendOrDelete($watchingUserId)
    {
        $currentUser = $this->getUser();
        if ($watchingUserId === $currentUser->getId() || $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get the id with each vern name, bind it and store into an array
     * @param $list
     * @return array
     */
    private function bindVernWithId($list)
    {
        $vernList = [];
        foreach ($list as $item) {
            foreach ($item as $bird) {
                $value = explode("=>", $bird);
                $vernList[$value[0]] = $value[1];
            }
        }
        return $vernList;
    }
}
