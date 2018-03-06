<?php

namespace Ornito\ObservationBundle\Controller;

use Ornito\ObservationBundle\Entity\Watching;
use Ornito\ObservationBundle\Form\WatchingType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ObservationController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrnitoObservationBundle:Observation:index.html.twig');
    }

    public function viewAction($id)
    {
        return $this->render('OrnitoObservationBundle:Observation:view.html.twig');
    }

    public function addAction(Request $request)
    {
        $watching = new Watching($this->getUser());
        $form = $this->createForm(WatchingType::class, $watching);

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($watching);
            $em->flush();

            $this->redirectToRoute('ornito_observation_view', array(
                'id' => $watching->getId(),
            ));
        }
        return $this->render('OrnitoObservationBundle:Observation:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function editAction(Request $request, $id)
    {
        return $this->render('OrnitoObservationBundle:Observation:edit.html.twig');
    }

    public function deleteAction(Request $request, $id)
    {
        return $this->render('OrnitoObservationBundle:Observation:delete.html.twig');
    }
}