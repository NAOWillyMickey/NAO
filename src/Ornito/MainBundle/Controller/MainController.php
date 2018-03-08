<?php

namespace Ornito\MainBundle\Controller;

use Ornito\ObservationBundle\Entity\Watching;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getManager()->getRepository('OrnitoObservationBundle:Watching');
        $lastWatching = $repo->findBy(
            array('validateStatus' => Watching::ATTESTED),
            array('date' => 'desc'),
            5,
            0
        );

        return $this->render('OrnitoMainBundle:Main:index.html.twig', array(
            'lastWatching' => $lastWatching,
        ));
    }

    public function legalAction()
    {
        return $this->render('OrnitoMainBundle:Main:legal.html.twig');
    }

    public function cguAction()
    {
        return $this->render('OrnitoMainBundle:Main:cgu.html.twig');
    }

    public function aboutUsAction()
    {
        return $this->render('OrnitoMainBundle:Main:about-us.html.twig');
    }

    public function joinUsAction()
    {
        return $this->render('OrnitoMainBundle:Main:join-us.html.twig');
    }
}