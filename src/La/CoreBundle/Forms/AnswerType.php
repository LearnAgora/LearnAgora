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


class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('answer');
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'La\CoreBundle\Entity\Answer',
            'csrf_protection'   => false
        ));
    }

    public function getName()
    {
        return 'form_answer';
    }
}
