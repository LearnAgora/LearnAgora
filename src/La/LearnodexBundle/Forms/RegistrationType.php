<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 09/09/14
 * Time: 22:16
 */

namespace La\LearnodexBundle\Forms;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RegistrationType extends AbstractType {

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getName()
    {
        return 'ln_user_registration_type';
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username','text', array('label' => 'Your name'))
            ->add('email','email', array('label' => 'Email address'))
            ->add('password','password', array('label' => 'Password'))
            ->add('create','submit', array('label' => 'Create account'));
    }
}