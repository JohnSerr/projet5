<?php

namespace P5\TodolistBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('P5TodolistBundle:Default:index.html.twig');
    }
}
