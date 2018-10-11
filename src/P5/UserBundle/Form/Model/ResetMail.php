<?php

namespace P5\UserBundle\Form\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

class ResetMail
{
	/**
	* @Assert\Email(checkMX=false, message="Ce n'est pas une adresse valide")
	*/
	protected $resetmail;

	/**
	* Get resetmail
	*
	* @return ResetMail
	*/
	public function getResetmail()
	{
		return $this->resetmail;
	}

	/**
	* Set resetmail
	*
	* @param string $resetmail
	*
	* @return string
	*/
	public function setResetmail($resetmail)
	{
		$this->resetmail = $resetmail;

		return $this;
	}
}