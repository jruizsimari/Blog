<?php

namespace Sdz\BlogBundle\Form\EventListener;



use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
/**
* 
*/
class AddPublicationSubscriber implements EventSubscriberInterface
{
	
	public static function getSubscribedEvents()
	{
		// dit au dispatcher que vous voulez écouter l'évènement
		// form.pre_set_data et que la méthode preSetData doit être appelée
		return array(FormEvents::PRE_SET_DATA => 'preSetData');
	}

	public function preSetData(FormEvent $event)
	{
		$article = $event->getData();

		$form = $event->getForm();

		if($article === null) {
			return;
		}

		if (!$article || false === $article->getPublication()) {
			$form->add('publication', 'checkbox', array('required' => false));
		}
		else {
			$form->remove('publication');
		}
	}
}
