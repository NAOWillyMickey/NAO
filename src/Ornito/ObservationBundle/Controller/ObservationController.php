<?php

namespace Ornito\ObservationBundle\Controller;

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
        return $this->render('OrnitoObservationBundle:Observation:add.html.twig');
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