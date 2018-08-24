<?php

namespace P5\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('P5UserBundle:Default:index.html.twig');
    }
}
