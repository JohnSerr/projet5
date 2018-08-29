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
		/* On récupère l'id du membre connecté*/

        $user = $this->getUser();
		$userid = $user->getId();

		/*On récupère l'utilisateur*/		
 
		$em =$this->getDoctrine()->getManager();

		$useroflist = $em->getRepository("P5UserBundle:User")->find($userid);

		/*On récupère tout les rappels lié a cet utilisateur*/

		$entirelist = $em->getRepository("P5TodolistBundle:todolist")->findBy(
		array("user" => $useroflist),	
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
		
		$formBuilder = $this->get("form.factory")->createBuilder(todolistType::class, $todo);
		
		$addform = $formBuilder->getForm();

			if($request->isMethod("POST")) {

				$addform->handleRequest($request);
                    
                    /*On vérifie la validité du formulaire*/

					if($addform->isValid()) {

					/* On récupère l'id du membre connecté*/

        			$user = $this->getUser();
					$userid = $user->getId();

					/*On récupère l'utilisateur*/		
 
					$em =$this->getDoctrine()->getManager();
					$useraddlist = $em->getRepository("P5UserBundle:User")->find($userid);
					$author = $useraddlist->getUsername();

					/* On "set" 3 attributs*/
					$todo->setUser($useraddlist);
					$todo->setAuthor($author);
					$todo->setDate(new \Datetime());

						
                    $em->persist($todo);
					$em->flush();
					$request->getSession()->getFlashBag()->add('notice', 'Ajouté à la liste');
					
					return $this->redirectToroute("p5_todolist_view");
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

		$author = $todo->getUser();
		$authorid = $author->getId();
		$user = $this->getUser();
		$userid = $user->getId();

		if ($userid == $authorid) {

			$formBuilder = $this->get("form.factory")->createBuilder(todolistType::class, $todo);
			$editform = $formBuilder->getForm();

			if($request->isMethod("POST")) {

				$editform->handleRequest($request);

				if($editform->isValid()) {

					$em->flush();

					return $this->redirectToroute("p5_todolist_view");
				}	

			}
			
			return $this->render("P5TodolistBundle:todolist:edit.html.twig", array(
			"form" => $editform->createView()
	    	));
		
		} else {
			throw new AccessDeniedException("Acces Denied");
		} 
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

			$author = $todo->getUser();
			$authorid = $author->getId();
			$user = $this->getUser();
			$userid = $user->getId();

			if($request->isMethod("POST")) {

				$em->remove($todo);
				$em->flush();

				return $this->redirectToroute("p5_todolist_view");				

			}

		return $this->render("P5TodolistBundle:todolist:delete.html.twig", array(
			"todo" => $todo
		));

	}

}