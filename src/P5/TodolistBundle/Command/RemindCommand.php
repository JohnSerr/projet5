<?php

namespace P5\TodolistBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use P5\TodolistBundle\Entity\todolist;
use P5\UserBundle\Entity\User;


class RemindCommand extends ContainerAwareCommand
{
	protected function configure() {

		$this->setName('remindmail');
		$this->setDescription('alerte l\'utilisateur par mail d\'un rappel programmé');
		$this->setHelp('Cmd créer pour cron d\'envoi quotidien de mail de rappel');

	}

	protected function execute(InputInterface $input, OutputInterface $output) {

		$container = $this->getContainer();

		$em = $container->get('doctrine.orm.entity_manager');

		$datenow = new \DateTime('00:00:00');
		
		$listtoremind = $em->getRepository("P5TodolistBundle:todolist")->findBy(
		array('dateofend' => $datenow,
			  'remind' => true
			 ),
		null,
		null,
		0
		);


		foreach($listtoremind as $remindtomail) {
			
			 $mail = $remindtomail->getUser()->getEmail();

			 echo $mail;

		}

	}
}