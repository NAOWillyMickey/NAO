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

        // The request method is "POST" & a value is selected in the form
        if ($request->isMethod('POST') && count($request->request) > 1) {
            $data = $request->request;
            $tab = [];
            // Push from request, data in an array
            foreach ($data as $key => $value) {
                array_push($tab, $key, $value);
            }
            // Get the 2 lines we need from the array for the next method parameters
            $output = array_slice($tab, -4,2);
            $birds = $repository->mySelectList($output[0], $output[1]);
            // If user change values of the select form
            if ($birds === null) {
                $request->getSession()->getFlashBag()->add('notice', 'Select a bird in the list please!!!');
            }
        } elseif ($request->isMethod('POST') && count($request->request) <= 1) {
            // User override the required field and send an empty form
            $request->getSession()->getFlashBag()->add('notice', 'You should select a bird in the select form before find it...');
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

    public function searchAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $repo = $this->getDoctrine()->getManager()->getRepository('OrnitoTaxrefBundle:Species');
            $req = $repo->findAllSpecies();
            return new JsonResponse(array('json' => $req), 200);
        }
    }
}