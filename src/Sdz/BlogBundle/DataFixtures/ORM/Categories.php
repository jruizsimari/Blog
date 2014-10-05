<?php

/**
* 
*/
namespace Sdz\BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Sdz\BlogBundle\Entity\Categorie;

class Categories implements FixtureInterface
{
	//Dans l'argument de la méthode load, l'objet $manager est l'EntityManager
	public function load(ObjectManager $manager)
	{
		// Liste des noms de catégorie à ajouter
		$noms = array('Symfony2', 'Doctrine', 'Evènement','Tutoriel');

		foreach ($noms as $i => $nom) {
			// On crée la catégorie
			$liste_categories[$i] = new Categorie();
			$liste_categories[$i]->setNom($nom);

			$manager->persist($liste_categories[$i]);
		}

		$manager->flush();

	}
}