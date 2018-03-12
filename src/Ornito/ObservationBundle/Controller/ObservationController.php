<?php

namespace Ornito\ObservationBundle\Controller;

use Ornito\ObservationBundle\Entity\Watching;
use Ornito\ObservationBundle\Form\WatchingType;
use Ornito\ObservationBundle\Form\WatchingValidateType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ObservationController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page '.$page.' inexistante.');
        }

        $nbPerPage = $this->container->getParameter('nb_per_page');
        $listWatching = $this->getDoctrine()
            ->getManager()
            ->getRepository('OrnitoObservationBundle:Watching')
            ->getObs($page, $nbPerPage, Watching::ATTESTED)
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

        $authorization = $this->canAmendOrDelete($watching);

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

        $authorization = $this->canAmendOrDelete($watching);

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
     * @Security("has_role('ROLE_NATURALIST')")
     *
     * @param Request $request
     * @param $page
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function waitingAction(Request $request, $page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Page '.$page.' inexistante.');
        }

        $nbPerPage = $this->container->getParameter('nb_per_page');
        $listWatching = $this->getDoctrine()
            ->getManager()
            ->getRepository('OrnitoObservationBundle:Watching')
            ->getObs($page, $nbPerPage, Watching::UNTREATED)
        ;

        $nbWatching = count($listWatching);

        if ($nbWatching <= 0) {
            $request->getSession()->getFlashBag()->add('info', 'Aucune observation en attente de validation');
            return $this->redirectToRoute('ornito_observation_homepage');
        }

        $nbPages = ceil($nbWatching / $nbPerPage);

        if ($page > $nbPages) {
            throw $this->createNotFoundException("La page ".$page." n'existe pas.");
        }

        return $this->render('OrnitoObservationBundle:Observation:waiting.html.twig', array(
            'listWatching' => $listWatching,
            'page' => $page,
            'nbPages' => $nbPages,
        ));
    }


    /**
     * @Security("has_role('ROLE_NATURALIST')")
     *
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function validateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $watching = $em
            ->getRepository('OrnitoObservationBundle:Watching')
            ->find($id)
        ;
        $currentStatus = $watching->getValidateStatus();

        if ($watching === null) {
            throw new NotFoundHttpException('L\'observation d\'id ' . $id . ' n\'existe pas!');
        }

        $form = $this->createForm(WatchingValidateType::class, $watching);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            if ( $currentStatus != Watching::UNTREATED) {
                $request->getSession()->getFlashBag()->add('warning', 'L\'observation a déjà été traitée et a reçu le status : ' .$currentStatus);
            } else {
                $postStatus = $request->request->get('watching_validate')['validateStatus'];
                $em->flush();
                $request->getSession()->getFlashBag()->add('success', 'L\'observation a bien reçu le status : ' . $postStatus);
            }
            return $this->redirectToRoute('ornito_observation_waiting');
        }

        return $this->render('OrnitoObservationBundle:Observation:validate.html.twig', array(
            'form' => $form->createView(),
            'watch' => $watching,
        ));
    }

    /**
     * This return true if current user is the same who ask for action and observation is not attested
     * @param $watching
     * @return bool
     */
    private function canAmendOrDelete($watching)
    {
        $currentUser = $this->getUser();
        $watchingUserId = $watching->getUser()->getId();
        if ($this->get('security.authorization_checker')->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        } else if ($watchingUserId === $currentUser->getId() && $this->get('security.authorization_checker')->isGranted('ROLE_NATURALIST')){
            return true;
        }
        else if ($watchingUserId === $currentUser->getId() && $watching->getValidateStatus() != Watching::ATTESTED) {
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
