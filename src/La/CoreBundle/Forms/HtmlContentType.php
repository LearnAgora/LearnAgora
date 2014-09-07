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


class HtmlContentType extends AbstractType
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