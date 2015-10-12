<?php

namespace Symsite\Bundle\BlogBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symsite\Bundle\BlogBundle\Entity\Article;
use Symsite\Bundle\BlogBundle\Entity\Category;
use Symsite\Bundle\BlogBundle\Entity\Tag;

class ArticleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
          ->add('title', 'text', ['label' => 'article.title'])
          ->add('body', 'textarea', [
            'label' => 'article.body',
            'attr' => [
              'rows' => '10',
              'class' => 'tinymce',
            ]
          ])
          ->add('category', 'entity', [
            'class' => Category::class,
            'data_class' => Category::class,
            'property' => 'name',
            'label' => 'article.category',
            'placeholder' => 'None',
            'expanded' => true,
          ])
          ->add('tags', 'entity', [
            'class' => Tag::class,
            'multiple' => true,
            'property' => 'name',
            'attr' => [
              'class' => 'tags'
            ],
          ])
          ->add('status', 'choice', [
            'label' => 'article.status',
            'choices' => array_combine(Article::$statuses, Article::$statuses),
            'required' => true,
          ])
          ->add('publishedAt', 'datetime', ['label' => 'article.publishedAt']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
          'data_class' => Article::class
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
