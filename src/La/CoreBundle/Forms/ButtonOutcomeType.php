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


class ButtonOutcomeType extends AbstractType
{
    private $path;

    public function __construct($path="#")
    {
        $this->path = $path;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('caption')
                ->add('affinity');
    }


    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'La\CoreBundle\Entity\ButtonOutcome',
            'csrf_protection' => false,
        ));
    }

    public function getName()
    {
        return 'form_button_outcome';
    }
}
