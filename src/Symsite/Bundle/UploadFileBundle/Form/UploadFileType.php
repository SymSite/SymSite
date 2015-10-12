<?php

namespace Symsite\Bundle\UploadFileBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symsite\Bundle\UploadFileBundle\Entity\UploadFile;

class UploadFileType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('file', 'file', [
                'label' => false,
                'error_bubbling' => true
            ])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => UploadFile::class,
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'upload_file';
    }
}
