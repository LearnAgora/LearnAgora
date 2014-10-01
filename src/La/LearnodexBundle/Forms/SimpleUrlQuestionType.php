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
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class SimpleUrlQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('#')
            ->add('instruction','text', array(
                'label' => 'Instruction',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter instructions',
                ),
                //'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('url','text', array(
                'label' => 'Url',
                'attr' => array(
                    'class' => 'form-control',
                    'placeholder' => 'Enter URL',
                ),
                //'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('question','textarea', array(
                'label' => 'Question',
                'attr' => array(
                    'rows' => 3,
                    'class' => 'form-control',
                    'placeholder' => 'Enter question',
                ),
              //  'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('answers', 'collection', array(
                'type' => new AnswerType(),
                'label_attr'=> array('class'=>'sr-only'),
            ))
            ->add('create','submit', array('label' => 'Save'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'La\CoreBundle\Entity\SimpleUrlQuestion',
        ));
    }

    public function getName()
    {
        return 'question';
    }

} 