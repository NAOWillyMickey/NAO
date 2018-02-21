<?php

namespace Ornito\ResearchBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ResearchController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrnitoResearchBundle:Research:index.html.twig');
    }
}