<?php

namespace Ornito\MapBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MapController extends Controller
{
    public function indexAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OrnitoTaxrefBundle:Species');

        $req = $repository->findColumns();
        $ordreList = $this->filter(array_column($req, 'ordre'));
        $familyList = $this->filter(array_column($req, 'family'));
        return $this->render('OrnitoMapBundle:Map:index.html.twig', array(
            'ordreList' => $ordreList,
            'familyList' => $familyList
        ));
    }

    public function filter($tab)
    {
        $list = array();
        foreach ($tab as $value) {
            if (!in_array($value, $list)) {
                $list[] = $value;
            }
        }
        return $list;
    }

    public function searchAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $repo = $this->getDoctrine()->getManager()->getRepository('OrnitoTaxrefBundle:Species');
            //$reqName = 'findBy'.$column;
            //$req = $repo->$reqName($value);
            $req = $repo->findAll();
            return new JsonResponse(array('req' => $req));
        }
    }
}
