<?php

namespace Ornito\TaxrefBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class TaxrefController extends Controller
{
    public function indexAction(Request $request)
    {
        $birds = null;
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OrnitoTaxrefBundle:Species');

        if ($request->isMethod('POST')) {
            $data = $request->request;
            $tab = [];
            foreach ($data as $key => $value) {
                array_push($tab, $key, $value);
            }
            for ($i = 0; $i < 2; $i++) {
                array_pop($tab);
            }
            $output = array_slice($tab, -2);
            $birds = $repository->mySelectList($output[0], $output[1]);
        }

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
            'birds' => $birds,
        ));
    }

    public function findAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $species = $em->getRepository('OrnitoTaxrefBundle:Species')->find($id);

        if (null === $species) {
            throw new NotFoundHttpException("L'annonce d'id ".$id." n'existe pas.");
        }

        return $this->render('OrnitoTaxrefBundle:Taxref:find.html.twig', array(
            'bird' => $species,
        ));
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