<?php

namespace Ornito\TaxrefBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaxrefController extends Controller
{
    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OrnitoTaxrefBundle:Species');

        $ordreList = $repository->getList('ordre');
        $familyList = $repository->getList('family');
        $scientificList = $repository->getList('scientificName');
        $vernList = $repository->getList('vernFr');
        unset($vernList[0]); // Delete the first occurrence of the vernList array which is empty

        return $this->render('OrnitoTaxrefBundle:Taxref:index.html.twig', array(
            'ordreList' => $ordreList,
            'familyList' => $familyList,
            'scientificList' => $scientificList,
            'vernList' => $vernList,
        ));
    }

    public function findAction($id)
    {
        return $this->render('OrnitoTaxrefBundle:Taxref:find.html.twig');
    }

    public function searchAction(Request $request, $name, $value)
    {
        if ($request->isXmlHttpRequest()) {
            $repo = $this->getDoctrine()->getManager()->getRepository('OrnitoTaxrefBundle:Species');
            $req = $repo->mySelectList($name, $value);
            return new JsonResponse(array('json' => $req), 200);
        }
    }
}