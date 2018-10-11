<?php

namespace P5\UserBundle\Controller;

use P5\UserBundle\Entity\User;
use P5\UserBundle\Form\UserType;
use P5\UserBundle\Form\ChangePasswordType;
use P5\UserBundle\Form\Model\ChangePassword;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
  	$inscriptionform->handleRequest($request);

  	   	if($inscriptionform->isValid() && $inscriptionform->isSubmitted()) {

  			 	 	$newmember->setRoles(array('ROLE_MEMBER'));
  					$newmember->setSalt("sha512");
  					$pass = $newmember->getPassword();
  					$encoder = $this->get('security.encoder_factory')->getEncoder($newmember);
  					$encodedpass = $encoder->encodePassword($pass, $newmember->getSalt());
  					$newmember->setPassword($encodedpass);

  					$em = $this->getDoctrine()->getManager();
  					$em->persist($newmember);
  					$em->flush();

  					return $this->redirectToRoute('login');
  			}	
  		
  	return $this->render('P5UserBundle:Security:inscription.html.twig' , array(
  		"inscriptionform" => $inscriptionform->createView()
  	));
  }

  /**
	* @Security("has_role('ROLE_MEMBER')")
  */
  public function resetpasswordAction(Request $request)
  {	

		$editpass = new ChangePassword();
  	$formbuilder = $this->get("form.factory")->createBuilder(changePasswordType::class, $editpass);
   	$changeform = $formbuilder->getForm();
  	$changeform->handleRequest($request);

  	 	  if($changeform->isValid() && $changeform->isSubmitted()) {

				    $pass = $changeform["newpassword"]->getData();
        	  $em = $this->getDoctrine()->getManager();		
  	 			  $user = $this->getUser();		
					  $member = $em->getRepository('P5UserBundle:User')->find($user->getId($user));		
  	 			  $encoder = $this->get('security.encoder_factory')->getEncoder($member);
  	 			  $newpass = $encoder->encodePassword($pass, $member->getSalt());
        	  $member->setPassword($newpass);

					  $em->flush();

  	 				return $this->redirectToRoute("p5_core_home");
  	 			}
  	 	
  	return $this->render('P5UserBundle:Security:changepasswordview.html.twig', array(
  	    	'changeform'=> $changeform->createView()
  	));  
  }

}