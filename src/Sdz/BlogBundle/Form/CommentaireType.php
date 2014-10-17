<?php

namespace Sdz\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CommentaireType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('auteur', 'text')
            ->add('contenu', 'textarea')
            ->add('date', 'date')
            ->add('article', 'entity', array(
                                          'class'    => 'SdzBlogBundle:Article',
                                          'property' => 'titre',
                                          'querybuilder' => function (\Sdz\BlogBundle\Entity\ArticleRepository $r) {
                                                                return $r->getSelectList();
                                          }))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Sdz\BlogBundle\Entity\Commentaire'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'sdz_blogbundle_commentaire';
    }
}
