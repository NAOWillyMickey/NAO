<?php

namespace Ornito\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MainController extends Controller
{
    public function indexAction($page)
    {
        if ($page < 1) {
            throw new NotFoundHttpException('Timeline Actu "'.$page.'" indisponible pour le moment.');
        }
        return $this->render('OrnitoMainBundle:Main:index.html.twig');
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