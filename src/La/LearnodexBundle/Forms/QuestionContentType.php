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


class QuestionContentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction('')
            ->add('instruction','textarea', array(
                'label' => 'Instruction',
                'attr' => array(
                    'rows' => 2,
                    'class' => 'form-control',
                    'placeholder' => 'Enter instructions',
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
            ->add('answers', 'collection', array('type' => new AnswerType()))
            ->add('create','submit', array('label' => 'Save'));
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'La\CoreBundle\Entity\QuestionContent',
        ));
    }

    public function getName()
    {
        return 'HtmlContent';
    }

} 