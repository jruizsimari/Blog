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

		return $this->render('SdzBlogBundle:Blog:index.html.twig', array('page' => $page));
	}

	public function voirAction($id)
	{

		return $this->render('SdzBlogBundle:Blog:voir.html.twig', array('id' => $id));
	}

	public function ajouterAction() {
		if( $this->get('request')->getMethod() === 'POST') {
			// Ici, on s'occupera de la création et de la gestion du formulaire
			$this->get('session')->getFlashBag()->add('notice', 'Article bien enregistré');

			return $this->redirect($this->generateUrl('sdzblog_voir', array('id' => 5)));
		}

		return $this->render('SdzBlogBundle:Blog:ajouter.html.twig');
	}


	public function modifierAction($id) {
		return $this->render('SdzBlogBundle:Blog:modifier.html.twig', array('id' => $id,));
	}

	public function supprimerAction($id) {
		return $this->render('SdzBlogBundle:Blog:supprimer.html.twig');
	}

}
