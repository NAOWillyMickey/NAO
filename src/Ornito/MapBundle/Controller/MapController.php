<?php

namespace Ornito\MapBundle\Controller;

use Ornito\ObservationBundle\Entity\Watching;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MapController extends Controller
{
    public function indexAction(Request $request)
    {
        $watchingsList = 1;
        $watchingsListJson = [];
        $birds = null;
        $repository = $this->getDoctrine()->getManager()->getRepository('OrnitoTaxrefBundle:Species');
        $repo = $this->getDoctrine()->getManager()->getRepository('OrnitoObservationBundle:Watching');
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
            if ($birds === null) { $request->getSession()->getFlashBag()->add('danger', 'Select a bird in the list please!!!'); }
            else {
              $watchingsList = [];
              $watchingsListJson = [];
              foreach ($birds as $bird) {
                foreach ($bird as $key => $val) {
                  if ($key == 'id') {
                    $watchings_bird_id = $val;
                    $watchings = $repo->findObsByBirdSelector($watchings_bird_id);
                    $watchingsJson = $repo->findObsByBirdSelector($watchings_bird_id, 'json');
                    if ($watchings != null) {
                      $watchingsList[$val] = $watchings;
                      $watchingsListJson[$val] = $watchingsJson;
                    }
                  }
                }
              }
            }
        }
        elseif ($request->isMethod('POST') && count($request->request) <= 1) {
          // User override the required field and send an empty form
          $request->getSession()->getFlashBag()->add('danger', 'You should select a bird in the select form before find it...');
        }

        $watchingsListJson = Json_encode($watchingsListJson);
        $ordreList = $repository->getList('ordre');
        $familyList = $repository->getList('family');
        $scientificList = $repository->getList('scientificName');
        $vernList = $repository->getList('vernFr');
        unset($vernList[0]); // Delete the first occurrence of the vernList array which is empty

        return $this->render('OrnitoMapBundle:Map:index.html.twig', array(
            'ordreList' => $ordreList,
            'familyList' => $familyList,
            'scientificList' => $scientificList,
            'vernList' => $vernList,
            'watchingsList' => $watchingsList,
            'watchingsListJson' => $watchingsListJson,
            'birds' => $birds,
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
