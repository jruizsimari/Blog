<?php

namespace Sdz\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\BlogBundle\Entity\Competence;

/**
* 
*/
class Competences implements FixtureInterface
{
	
	public function load(ObjectManager $manager)
	{
		// Liste des noms de compétences à ajouter
		$noms = array('Doctrine', 'Formulaire', 'Twig');

		foreach ($noms as $i => $nom) {
			$liste_competences[$i] = new Competence();
			$liste_competences[$i]->setNom($nom);

			$manager->persist($liste_competences[$i]);
		}

		$manager->flush();
	}
}