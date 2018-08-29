<?php
// src/P5/UserBundle/DataFixtures/ORM/LoadUser.php

namespace P5\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use P5\UserBundle\Entity\User;

class LoadUser implements FixtureInterface
{
  public function load(ObjectManager $manager)
  {
    // Les noms d'utilisateurs à créer
    $listNames = array('Alexandre');

    foreach ($listNames as $name) {
      // On crée l'utilisateur
      $user = new User;

      
      $user->setUsername($name);
      $user->setPassword($name);

      $user->setSalt('');
      
      $user->setRoles(array('ROLE_MEMBER'));

      $user->setEmail("truc@truc.com");
      // On le persiste
      $manager->persist($user);
    }

    // On déclenche l'enregistrement
    $manager->flush();
  }
}