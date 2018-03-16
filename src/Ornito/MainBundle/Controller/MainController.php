<?php

namespace Ornito\MainBundle\Controller;

use Ornito\ObservationBundle\Entity\Watching;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
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

    public function setLocaleAction($language = null, Request $request)
    {
        if($language != null)
        {
            // On enregistre la langue en session
            $this->get('session')->set('_locale', $language);
        }

        // on tente de rediriger vers la page d'origine
        $url = $request->headers->get('referer');
        if(empty($url))
        {
            $url = $this->container->get('router')->generate('ornito_main_homepage');
        } else {

            $previousLocale = '/'. $request->attributes->get('_locale') .'/';
            $askedLocale = '/'. $language .'/';
            $url = str_replace($previousLocale, $askedLocale, $url);
        }

        return new RedirectResponse($url);
    }
}