<?php

namespace P5\UserBundle\Form\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
	/**
     
    */ 
	protected $oldpassword;

	/**
	 *@Assert\Length(min=6, minMessage="Votre mot de passe doit contenir 6 caractères.")
	*/
	protected $newpassword;

	    /**
     * Set Oldpassword
     *
     * @param string $oldpassword
     *
     * @return ChangePassword
     */
    public function setOldpassword($oldpassword)
    {
        $this->password = $oldpassword;

        return $this;
    }

    /**
     * Get getOldpassword
     *
     * @return string
     */
    public function getOldpassword()
    {
        return $this->oldpassword;
    }

    /**
     * Set Newpassword
     *
     * @param string $newpassword
     *
     * @return ChangePassword
     */
    public function setNewpassword($newpassword)
    {
        $this->password = $newpassword;

        return $this;
    }

    /**
     * Get getNewpassword
     *
     * @return string
     */
    public function getNewpassword()
    {
        return $this->newpassword;
    }

}
