<?php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Entity\Image;

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
		// On récupère le repository
		$repository = $this->getDoctrine()
						   ->getManager()
						   //->find('SdzBlogBundle:Article', $id);
		                   ->getRepository('SdzBlogBundle:Article');

		// On récupère l'entité correspondante à l'id $id
		$article = $repository->find($id);

		if ($article === null) {
			throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
		}

		return $this->render('SdzBlogBundle:Blog:voir.html.twig', array('article' => $article));
	}

	public function ajouterAction() {


		if( $this->get('request')->getMethod() === 'POST') {

			// contenu saisi par l'utilisateur
			$contenu = 'bla@hotmail.fr, bli@hotmail.com, johndoe@gmail.com';

			// On récupère le service antispam
			$antispam = $this->container->get('sdz_blog.antispam');

			if($antispam->isSpam($contenu)) {
				throw new Exception('Votre message a été détecté comme spam !');
			}
			return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => $article->getId())));
		}
		// Le message n'est pas un spam, on continue l'action...

			// Création de l'entité
			$article = new Article();
			$article->setTitre("Mon dernier weekend");
			$article->setAuteur('jruizsimari');
			$article->setContenu("C'était vraiment super et on s'est bien amusé.");

			// On ne peut pas définir ni la date ni la publication,
			// car ces attributs sont définis automatiquement dans le constructeur

			// Création de l'entité Image
			$image = new Image();
			$image->setUrl('https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT1pp6eAzUmHvWzb5K0q6iF2AAtl7wQWDtQsTc1x05Qi3n24XSl-fZ928k');
			$image->setAlt('homer Simpson');

			// J'associe l'image à l'article
			$article->setImage($image);

			// On récupère l'entité manager
			$em = $this->getDoctrine()->getManager();

			// Etape 1 : On "persiste" l'entité
			$em->persist($article);
			// Puisqu'on avait pas mis le cascade={"persist"}, on 
			// doit persister l'entité $image
			// $em->persist($image);

			// Etape 2 : On "flush" tout ce qui a été persisté avant
			$em->flush();

			// Ici, on s'occupera de la création et de la gestion du formulaire
			$this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');


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
			array('id' => 12, 'titre' => 'Petit test'),
			);

	    return $this->render('SdzBlogBundle:Blog:menu.html.twig', array('liste_articles' => $liste));
	    // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaire au template !
	}

}
