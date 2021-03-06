<?php

namespace P5\UserBundle\Controller;

use P5\UserBundle\Entity\User;
use P5\UserBundle\Form\UserType;
use P5\UserBundle\Form\ChangePasswordType;
use P5\UserBundle\Form\ResetMailType;
use P5\UserBundle\Form\SetNewPasswordUserType;
use P5\UserBundle\Form\Model\ChangePassword;
use P5\UserBundle\Form\Model\ResetMail;
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
use Symfony\Component\Finder\Exception\AccessDeniedException;

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
  					$newmember->setSalt("bcrypt");
  					$pass = $newmember->getPassword();
  					$encoder = $this->get('security.encoder_factory')->getEncoder($newmember);
  					$encodedpass = $encoder->encodePassword($pass, $newmember->getSalt());
  					$newmember->setPassword($encodedpass);

  					$em = $this->getDoctrine()->getManager();
  					$em->persist($newmember);
  					$em->flush();

            $this->addFlash('notice', 'Votre inscription a été prise en compte.');

  					return $this->redirectToRoute('p5_user_inscription');
  			}	
  		
  	return $this->render('P5UserBundle:Security:inscription.html.twig' , array(
  		"inscriptionform" => $inscriptionform->createView()
  	));
  }

  /**
	* @Security("has_role('ROLE_MEMBER')")
  */
  public function changePasswordAction(Request $request)
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

  public function resetPasswordAction(Request $request)
  {
      $membermail = new ResetMail();
      $formbuilder = $this->get('form.factory')->createBuilder(ResetMailType::class, $membermail);
      $form = $formbuilder->getForm();
      $form->handleRequest($request);

          if($form->isValid() && $form->isSubmitted()) {
              $em = $this->getDoctrine()->getManager();
              $member = $em->getRepository('P5UserBundle:User')->getUserByMail($form['resetmail']->getData());
                  if($member !== null) {
                    $ticket = uniqid();
                    $member->setTicketPassword($ticket);
                    $message = (new \Swift_Message('Reset Password'))
                            ->setFrom("smartreminder@ephemere-opc.ovh")
                            ->setTo($member->getEmail())
                            ->setBody(
                              $this->render('P5UserBundle:Emails:mailresetpassword.html.twig',
                                     array("ticket" => $ticket)),
                              'text/html'
                            );
                    $em->flush();
                    $mailer = $this->get("mailer");
                    $mailer->send($message);

                    $this->addFlash('notice', 'Un mail a été envoyé à cette adresse !');

                    return $this->redirectToRoute('p5_user_resetpass');                   
                  } else {

                    $this->addFlash('notice', 'Un mail a été envoyé à cette adresse !');
                    return $this->redirectToRoute('p5_user_resetpass');
                  }
          }
    return $this->render('P5UserBundle:Security:resetpassword.html.twig', array(
           'form' => $form->createView()    
    ));
  }

  public function setNewPasswordAction(Request $request, $ticket)
  {

    $em = $this->getDoctrine()->getManager();
    $member = $em->getRepository("P5UserBundle:User")->getUserByTicket($ticket);

        if($member !== null) {

            $formbuilder = $this->get('form.factory')->createBuilder(SetNewPasswordUserType::class, $member);
            $form = $formbuilder->getForm();
            $form->handleRequest($request);

                if($form->isValid() && $form->isSubmitted()) {
                    $plainpass = $form["password"]->getData();
                    $encoder = $this->get('security.encoder_factory')->getEncoder($member);
                    $newpass = $encoder->encodePassword($plainpass, $member->getSalt());
                    $member->setPassword($newpass);
                    $member->setTicketPassword(null);

                    $em->flush();

                    return $this->redirectToRoute('login');   
                
                } else

            return $this->render('P5UserBundle:Security:setnewpassword.html.twig', array( 'form' => $form->createView()));  

      } else {

       throw new AccessDeniedException("Acces Denied");

      }
  }

}