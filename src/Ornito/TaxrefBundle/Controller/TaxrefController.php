<?php

namespace Ornito\TaxrefBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Translation\TranslatorInterface;

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
                $translatedMessage = $this->get('translator')->trans('Sélectionnez un oiseau dans la liste s\'il vous plaît !!!');
                $request->getSession()->getFlashBag()->add('danger', $translatedMessage);
            }
        } elseif ($request->isMethod('POST') && count($request->request) <= 1) {
            // User override the required field and send an empty form
            $translatedMessage = $this->get('translator')->trans('Vous devez sélectionner un oiseau dans le formulaire de sélection avant de le trouver ...');
            $request->getSession()->getFlashBag()->add('danger', $translatedMessage);
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