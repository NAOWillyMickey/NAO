<?php

namespace Ornito\ObservationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ObservationController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrnitoObservationBundle:Observation:index.html.twig');
    }
}
