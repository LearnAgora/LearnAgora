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


class AffinityOutcomeType extends AbstractType
{
    private $path;

    public function __construct($path="#")
    {
        $this->path = $path;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setAction($this->path)
            ->add('operator','choice', array('choices' => array(
                '>' => 'is bigger than',
                '<' => 'is smaller than'
            ),'label' => 'Operator','label_attr'=> array('class'=>'sr-only')))
            ->add('treshold','percent', array('label' => 'Treshold', 'max_length' => 2,'label_attr'=> array('class'=>'sr-only')))
            ->add('create','submit', array('label' => 'update','attr' => array('value' => 'AffinityOutcome')));
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'La\CoreBundle\Entity\AffinityOutcome',
        ));
    }

    public function getName()
    {
        return 'affinityoutcome';
    }
}
