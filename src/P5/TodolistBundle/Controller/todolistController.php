<?php

// src/OC/TodolistBundle/Controller/todolistController.php

namespace P5\TodolistBundle\Controller;

use P5\TodolistBundle\Entity\todolist;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class todolistController extends Controller
{
	public function indexAction()
	{
		return $this->render("P5TodolistBundle:todolist:view.html.twig");
		
	}

	public function addAction()
	{

	}

	public function editAction()
	{

	}
	
	public function deleteAction()
	{

	}

}