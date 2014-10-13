<?php

namespace Sdz\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Sdz\BlogBundle\Entity\Article;
use Sdz\BlogBundle\Entity\Image;
use Sdz\BlogBundle\Entity\Commentaire;
use Sdz\BlogBundle\Entity\Categorie;
use Sdz\BlogBundle\Entity\ArticleCompetence;

class BlogController extends Controller
{
	public function indexAction($page) {
		
		// On récupère tous les articles de la base
		$articles = $this->getDoctrine()
		                 ->getManager()
		                 ->getRepository('SdzBlogBundle:Article')
		                 ->getArticles(1, $page);

		return $this->render('SdzBlogBundle:Blog:index.html.twig', array('articles'   => $articles,
			                                                             'page'       => $page,
			                                                             'nombrePage' => ceil(count($articles)/1)                         
			                                                            ));
	}

	public function voirAction($slug)
	{
		// On récupère le repository
		$em = $this->getDoctrine()
				   ->getManager();
				   //->find('SdzBlogBundle:Article', $id);

		// On récupère l'entité correspondante à l'id $id
		// $liste_artCommentsCatsImg = $em->getRepository('SdzBlogBundle:Article')->getArticleAvecCommentaires($id);
		$liste_artCommentsCatsImg = $em->getRepository('SdzBlogBundle:Article')->getArtCommentsCatsCompsImg($slug);
		
		// On recupère en résultat un array(objet) de type Article dans notre cas un seul résultat
		// d'où l'indice 0 pour récupèrer le premier élément du tableau
		$article = $liste_artCommentsCatsImg[0];

		if ($article === null) {
			throw $this->createNotFoundException('Article[slug='.$slug.'] inexistant');
		}

		// On récupère la liste des commentaires
		// la méthode utilisée recupère tous les commentaires de la table 'commentaire'
		$liste_commentaires = $article->getCommentaires();

		// l'equivalent d'un var_dump.
		//exit(\Doctrine\Common\Util\Debug::dump($liste_commentaires));

		// Récupère les articleCompétence d'un article, d'slug $article->getslug()
		// $liste_articleCompetence = $em->getRepository('SdzBlogBundle:ArticleCompetence')->findByArticle($article->getslug());
		$articleCompetences = $em->getRepository('SdzBlogBundle:Article')->getArticleCompetences($slug);
		$liste_articleCompetence = $articleCompetences[0]->getArticleCompetence();


		return $this->render('SdzBlogBundle:Blog:voir.html.twig', array(
							'article'            => $article,
							'liste_commentaires' => $liste_commentaires,
							'liste_articleCompetence' => $liste_articleCompetence));
	}

	public function ajouterAction() {


		if( $this->get('request')->getMethod() === 'POST') {



			// Ici, on s'occupera de la création et de la gestion du formulaire
			$this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');
			
			// contenu saisi par l'utilisateur
			$contenu = 'bla@hotmail.fr, bli@hotmail.com, johndoe@gmail.com';

			// On récupère le service antispam
			$antispam = $this->container->get('sdz_blog.antispam');

			if($antispam->isSpam($contenu)) {
				throw new Exception('Votre message a été détecté comme spam !');
			}
			return $this->redirect($this->generateUrl('sdzblog_voir', array('slug' => $article->getSlug())));
		}
		


		return $this->render('SdzBlogBundle:Blog:ajouter.html.twig');
	}


	public function modifierAction(Article $article) {

		// On récupère l'EntityManager
		$em = $this->getDoctrine()
		           ->getManager();

		// On récupère l'entité correspondant à l'id $id
		// $article = $em->find('SdzBlogBundle:Article', $id);
		//$article = $em->getRepository('SdzBlogBundle:Article')->find($id);

		// if($article === null) {
		// 	throw $this->createNotFoundException('Article[id='.$id.'] inexistant !');
		// }

		// Ici, on s'occupera de la création et de la gestion du formulaire

		return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array('article' => $article));
	}

	public function supprimerAction(Article $article) {
		$em = $this->getDoctrine()
		           ->getManager();

		// $article = $em->getRepository('SdzBlogBundle:Article')->find($id);

		// if ($article === null) {
		// 	throw $this->createNotFoundException('Article[id='.$id.'] inexistant');
		// }

		if($this->get('request')->getMethod() == 'POST') {
			// Si la requête est en POST, on supprimera l'article

			$this->get('session')->getFlashBag()->add('info', 'Article supprimé');

			return $this->redirect( $this->generateUrl('sdzblog_accueil') );
		}

		return $this->render('SdzBlogBundle:Blog:supprimer.html.twig', array('article' => $article));
	}

	// Méthode du controller appelé par le layout général
	public function menuAction($nombre)
	{
		$em = $this->getDoctrine()
		           ->getManager();
		// Récupère une liste d'article de taille $nombre et dans l'ordre décroissant
		$liste = $em->getRepository('SdzBlogBundle:Article')->findBy(array(),                 // Pas de critères
		 															 array('date' => 'desc'), // On trie par date décroissante
		 															 $nombre,                 // On sélectionne $nombre articles
		 															 0                        // A partir du premier
		 															);

	    return $this->render('SdzBlogBundle:Blog:menu.html.twig', array('liste_articles' => $liste));
	    // C'est ici tout l'intérêt : le contrôleur passe les variables nécessaire au template !
	}

}
