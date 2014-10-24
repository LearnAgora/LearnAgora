<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/7/14
 * Time: 4:34 PM
 */

namespace La\LearnodexBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;


class UrlContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('#')
            ->add('instruction','textarea', array(
                'label' => 'Instruction',
                'attr' => array(
                    'rows' => 5,
                    'class' => 'form-control',
                    'placeholder' => 'Enter instructions',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('url','text', array(
                'label' => 'Url',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter URL',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => 'Save'));
    }

    public function getName()
    {
        return 'UrlContent';
    }
}
