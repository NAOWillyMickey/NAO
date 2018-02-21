<?php

namespace Ornito\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CoreController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrnitoCoreBundle:Core:index.html.twig');
    }
}