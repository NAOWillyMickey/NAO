<?php

namespace Ornito\TaxrefBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaxrefController extends Controller
{
    public function indexAction()
    {
        return $this->render('OrnitoTaxrefBundle:Taxref:index.html.twig');
    }

    public function findAction($id)
    {
        return $this->render('OrnitoTaxrefBundle:Taxref:find.html.twig');
    }

    public function searchAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {

            return new JsonResponse(array('parameters' => $request));
        }
        return new Response('Requête AJAX échouée...');
    }
}