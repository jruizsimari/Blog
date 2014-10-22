<?php

namespace Sdz\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

use Sdz\BlogBundle\Entity\CategorieRepository;

class ArticleType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', 'date', array(
                                        'widget' => 'single_text',
                                        'format' => 'dd/MM/yyyy'))
            ->add('titre', 'text')
            ->add('auteur', 'text')
            ->add('contenu','textarea')
            //->add('publication', 'checkbox', array('required' => false))
            ->add('image', new ImageType())
            ->add('categories', 'entity', array('class' => 'SdzBlogBundle:Categorie',
                                                'property' => 'nom',
                                                'query_builder' => function(CategorieRepository $er)
                                                                 {
                                                                   return $er->getCategoriesList();
                                                                 },
                                                'multiple' => true,
                                                'expanded' => false) )
        ;

        $factory = $builder->getFormFactory();

        // On ajoute une fonction qui va écouter l'évènement PRE_SET_DATA
        $builder->addEventListener(FormEvents::PRE_SET_DATA, // Ici, on définit l'évènement qui nous intéresse
                                   function (FormEvent $event) use ($factory)
                                   {
                                       $article = $event->getData();
                                       // Cette condition est importante, on en reparle plus loin
                                       if (null === $article) {
                                           return; // On sort de la fonction lorsque $article vaut null
                                       }

                                       // Si l'article n'est pas encore publié, on ajoute le champ publication
                                       if (false === $article->getPublication()) {
                                           $event->getForm()->add($factory->createNamed('publication', 'checkbox', null, array('required' => false)));
                                       } else {
                                        $event->getForm()->remove('publication');
                                       }
                                   });
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Article',
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            // une clé unique pour aider à la génération du jeton secret
            'intention'       => 'task_item',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'article';
    }
}
