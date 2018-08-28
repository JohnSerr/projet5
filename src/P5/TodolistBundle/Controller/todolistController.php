<?php

// src/OC/TodolistBundle/Controller/todolistController.php

namespace P5\TodolistBundle\Controller;

use P5\TodolistBundle\Entity\todolist;
use P5\TodolistBundle\Form\todolistType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;



class todolistController extends Controller
{
	/**
	 * @Security("has_role('ROLE_MEMBER')")
	 */
	public function indexAction(Request $request)
	{
		
        $user = $this->getUser();
		$userid = $user->getId();

		$em =$this->getDoctrine()->getManager();

		$entirelist = $em->getRepository("P5TodolistBundle:todolist")->findBy(
		array("authorid" => $userid),	
		array("date" => "desc"),
		null,
		0
		);

		return $this->render("P5TodolistBundle:todolist:view.html.twig", array(
		"entirelist" => $entirelist
		));
	}

	/**
	 * @Security("has_role('ROLE_MEMBER')")
	 */
	public function addAction(Request $request)
	{
		$todo = new todolist();

		$todo->setAuthor("Default");
		$todo->setAuthorid(1);
		$todo->setDate(new \Datetime());

		$formBuilder = $this->get("form.factory")->createBuilder(todolistType::class, $todo);
		
		$addform = $formBuilder->getForm();

			if($request->isMethod("POST")) {

				$addform->handleRequest($request);

					if($addform->isValid()) {

						$em = $this->getDoctrine()->getManager();
                        $em->persist($todo);
						$em->flush();
						$request->getSession()->getFlashBag()->add('notice', 'Ajouté à la liste');
					
						return $this->redirectToroute("p5_core_home");
					}
			}


		return $this->render("P5TodolistBundle:todolist:add.html.twig", array(
			"form" => $addform->createView(), 
		));


	}

	/**
	 * @Security("has_role('ROLE_MEMBER')")
	 */
	public function editAction($id, Request $request)
	{

		$em = $this->getDoctrine()->getManager();

		$todo = $em->getRepository("P5TodolistBundle:todolist")->find($id);

		if($todo === NULL) {

			throw new NotFoundHttpException ("Cette ligne n'existe pas.");

		}


		$formBuilder = $this->get("form.factory")->createBuilder(todolistType::class, $todo);

		$editform = $formBuilder->getForm();

			if($request->isMethod("POST")) {

				$editform->handleRequest($request);

				if($editform->isValid()) {

					$em->flush();

					$request->getSession()->getFlashBag()->add('notice', 'La liste a été éditée');

					return $this->redirectToroute("p5_core_home");

				}	

			}
			
		return $this->render("P5TodolistBundle:todolist:edit.html.twig", array(
		"form" => $editform->createView()
	    ));
	}
	
	/**
	 * @Security("has_role('ROLE_MEMBER')")
	 */
	public function deleteAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$todo = $em->getRepository("P5TodolistBundle:todolist")->find($id);

		if($todo === NULL) {

			throw new NotFoundHttpException("Ce rappel n'existe pas, inutile de le supprimer !");		
		}

			if($request->isMethod("POST")) {

				$em->remove($todo);
				$em->flush();

				return $this->redirectToroute("p5_core_home");				

			}

		return $this->render("P5TodolistBundle:todolist:delete.html.twig");

	}

}