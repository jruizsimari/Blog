<?php

namespace Sdz\BlogBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
* 
*/
class AntiFloodValidator extends ConstraintValidator
{
	
	function validate($value, Constraint $constraint)
	{
		// Pour l'instant, on considère comme flood l'erreur pour le 
		// formulaire, avec en argument le message
		if (strlen($value) < 3) {
			// C'est cette ligne qui déclenche l'erreur pour le formulaire,
			// avec en argument le message
			$this->context->addViolationé($constraint->message);
		}
	}
}