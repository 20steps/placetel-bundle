<?php

namespace twentysteps\Bundle\PlacetelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('twentystepsPlacetelBundle:Default:index.html.twig', array('name' => $name));
    }
}
