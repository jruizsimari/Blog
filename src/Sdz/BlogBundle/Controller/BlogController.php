<?php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class BlogController extends Controller
{
	public function indexAction($page) {
		if ($page < 1) {
			// On retourne automatiquement une erreur 404
			throw $this->createNotFoundException('Page inexistante (page = '.$page.')');


		}

		$articles = array(
				array('titre'  => 'Mon weekend a Phi Phi Island !',
				  'id'     => 1,
				  'auteur' => 'winzou',
				  'contenu'=> 'Ce weekend était trop bien. Blabla...',
				  'date'   => new \DateTime()),
				array('titre'  => 'Répétition du National Day de Singapour',
				  'id'     => 2,
				  'auteur' => 'winzou',
				  'contenu'=> 'Bientôt prêt pour le jour J. Blabla',
				  'date'   => new \DateTime()),
				array('titre'  => 'Chiffre d\'affaire en hausse',
				  'id'     => 3,
				  'auteur' => 'M@teo21',
				  'contenu'=> '+500% sur 1 an, fabuleux. Blabla...',
				  'date'   => new \DateTime()),
				);

		return $this->render('SdzBlogBundle:Blog:index.html.twig', array('articles' => $articles));
	}

	public function voirAction($id)
	{
		$article = array('titre'  => 'Mon weekend a Phi Phi Island !',
				  'id'     => 1,
				  'auteur' => 'winzou',
				  'contenu'=> 'Ce weekend était trop bien. Blabla...',
				  'date'   => new \DateTime());

		return $this->render('SdzBlogBundle:Blog:voir.html.twig', array('article' => $article));
	}

	public function ajouterAction() {

		// Le message n'est pas un spam, on continue l'action...

		if( $this->get('request')->getMethod() === 'POST') {

			// contenu saisi par l'utilisateur
			$contenu = 'bla@hotmail.fr, bli@hotmail.com, johndoe@gmail.com';

			// On récupère le service antispam
			$antispam = $this->container->get('sdz_blog.antispam');

			if($antispam->isSpam($contenu)) {
				throw new Exception('Votre message a été détecté comme spam !');
			}

			// Ici, on s'occupera de la création et de la gestion du formulaire
			$this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');

			return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => 5)));
		}

		return $this->render('SdzBlogBundle:Blog:ajouter.html.twig');
	}


	public function modifierAction($id) {
		$article = array('titre'  => 'Mon weekend a Phi Phi Island !',
				  'id'     => 1,
				  'auteur' => 'winzou',
				  'contenu'=> 'Ce weekend était trop bien. Blabla...',
				  'date'   => new \DateTime());

		return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array('article' => $article,));
	}

	public function supprimerAction($id) {
		return $this->render('SdzBlogBundle:Blog:supprimer.html.twig');
	}

	// Méthode du controller appelé par le layout général
	public function menuAction()
	{
		$liste = array(
			array('id' => 2, 'titre' => 'Mon dernier weekend'),
			array('id' => 5, 'titre' => 'Sortie de Symfony 2'),
			array('id' => 9, 'titre' => 'Petit test'),
			);

	    return $this->render('SdzBlogBundle:Blog:menu.html.twig', array('liste_articles' => $liste));
	    // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaire au template !
	}

}
