<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 10.07.15
 * Time: 10:58
 */

namespace La\CoreBundle\Forms;
use La\CoreBundle\Event\Listener\VariedCollectionSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


/**
 * @FormType()
 */
class VariedCollectionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // Tack on our event subscriber
        $builder->addEventSubscriber(new VariedCollectionSubscriber($builder->getFormFactory(), $options['type'], $options['type_cb']));
    }

    public function getParent()
    {
        return "collection";
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('type_cb'));
    }

    public function getName()
    {
        return "varied_collection";
    }

} 