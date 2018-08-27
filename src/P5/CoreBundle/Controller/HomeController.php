<?php

//src /P5/CoreBundle/Controller/homeController.php

namespace P5\CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
	public function homeAction()
    {
    	return $this->render("P5CoreBundle:Home:home.html.twig");
    }
}