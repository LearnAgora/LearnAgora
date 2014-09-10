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


class HtmlContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('#')
            ->add('content','textarea', array(
                'label' => 'Name',
                'attr' => array(
                    'rows' => 10,
                    'class' => 'form-control',
                    'placeholder' => 'Enter description',
                ),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => 'Save'));
    }

    public function getName()
    {
        return 'HtmlContent';
    }

} 