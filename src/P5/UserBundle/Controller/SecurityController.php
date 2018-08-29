<?php

namespace P5\UserBundle\Controller;

use P5\UserBundle\Entity\User;
use P5\UserBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class SecurityController extends Controller
{
	public function loginAction(Request $request)
  {
   
    if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
      return $this->redirectToRoute('p5_core_home');
    }

   	 $authenticationUtils = $this->get('security.authentication_utils');

    return $this->render('P5UserBundle:Security:login.html.twig', array(
      'last_username' => $authenticationUtils->getLastUsername(),
      'error'         => $authenticationUtils->getLastAuthenticationError(),
    ));
  }

  public function inscriptionAction(Request $request)
  {
  		$newmember = new User();
  		$formbuilder = $this->get("form.factory")->createBuilder(UserType::class, $newmember);
  		$inscriptionform = $formbuilder->getForm();

  		if($request->isMethod("POST")){
  			$inscriptionform->handleRequest($request);
  				if($inscriptionform->isValid()) {

  					$newmember->setRoles(array('ROLE_MEMBER'));
  					$newmember->setSalt("");

  					$em =$this->getDoctrine()->getManager();
  					$em->persist($newmember);
  					$em->flush();

  					return $this->redirectToRoute('p5_core_home');
  				}	
  		}
  
  	return $this->render('P5UserBundle:Security:inscription.html.twig' , array(
  		"inscriptionform" => $inscriptionform->createView()
  	));
  }


}