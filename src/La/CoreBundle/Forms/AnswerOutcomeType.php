<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/7/14
 * Time: 4:34 PM
 */

namespace La\CoreBundle\Forms;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AnswerOutcomeType extends AbstractType
{
    private $path;

    public function __construct($path="#")
    {
        $this->path = $path;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
//            ->setAction($this->path)
            ->add('selected','choice', array('choices' => array(
                '0' => 'is not selected',
                '1' => 'is selected'
            ),'label' => 'Operator','label_attr'=> array('class'=>'sr-only')))
            ->add('create','submit', array('label' => 'update','attr' => array('value' => 'AnswerOutcome')));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'La\CoreBundle\Entity\AnswerOutcome',
        ));
    }

    public function getName()
    {
        return 'answer_outcome';
    }

} 