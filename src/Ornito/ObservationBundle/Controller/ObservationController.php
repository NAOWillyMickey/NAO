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
        $user = $this->getUser();
        if (null === $user) {
            $request->getSession()->getFlashBag()->add('info', 'Connectez-vous ou créez un compte pour ajouter une observation');
            return $this->redirectToRoute('fos_user_registration_register');
        } else {
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

                return $this->render('OrnitoObservationBundle:Observation:add.html.twig', array(
                    'form' => $form->createView(),
                ));
            }
        }

    }

    public function editAction(Request $request, $id)
    {
        return $this->render('OrnitoObservationBundle:Observation:edit.html.twig');
    }

    public function deleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $watching = $em->getRepository('OrnitoObservationBundle:Watching')->find($id);

        if ($watching === null) {
            throw new NotFoundHttpException('L\'observation d\'id ' . $id . ' n\'existe pas!');
        }

        $watchingUserId = $watching->getUser()->getId();
        $currentUser = $this->getUser();

        // Available delete observation by the user who add the obs only or by super admin
        if ($watchingUserId === $currentUser->getId() || $this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            $em->remove($watching);
            $em->flush();
            $request->getSession()->getFlashBag()->add('success', 'L\'observation a bien été supprimée.');
            return $this->redirectToRoute('fos_user_profile_show');
        }
        $request->getSession()->getFlashBag()->add('warning', 'Vous n\'avez pas les droits nécessaires pour supprimer cette annonce!');
        return $this->redirectToRoute('fos_user_profile_show');
    }
}
