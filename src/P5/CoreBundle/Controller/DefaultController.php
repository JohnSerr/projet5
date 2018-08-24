<?php

namespace P5\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('P5CoreBundle:Default:index.html.twig');
    }
}
