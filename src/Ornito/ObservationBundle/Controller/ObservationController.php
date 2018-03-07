<?php

namespace Ornito\ObservationBundle\Controller;

use Ornito\ObservationBundle\Entity\Watching;
use Ornito\ObservationBundle\Form\WatchingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ObservationController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrnitoObservationBundle:Observation:index.html.twig');
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

        $em->remove($watching);
        $em->flush();
        $request->getSession()->getFlashBag()->add('success', 'L\'observation a bien été supprimée.');
        return $this->redirectToRoute('fos_user_profile_show');
    }
}
