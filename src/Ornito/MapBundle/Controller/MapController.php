<?php

namespace Ornito\MapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MapController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrnitoMapBundle:Map:index.html.twig');
    }
}
