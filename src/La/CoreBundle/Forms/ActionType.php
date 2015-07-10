<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/7/14
 * Time: 4:34 PM
 */

namespace La\CoreBundle\Forms;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Entity\AnswerOutcome;
use La\CoreBundle\Entity\ButtonOutcome;
use La\CoreBundle\Entity\UrlOutcome;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * @DI\FormType
 */
class ActionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $typeCb = function($datum) {
            if($datum instanceof AnswerOutcome)
            {
                return new AnswerOutcomeType();
            }
            elseif($datum instanceof ButtonOutcome)
            {
                return new ButtonOutcomeType();
            }
            elseif($datum instanceof UrlOutcome)
            {
                return new UrlOutcomeType();
            }
            else
            {
                return null; // Returning null tells the varied collection to use the default type - can be omitted, but included here for clarity
            }
        };
        $builder->add('name')
                ->add('content', new SimpleUrlQuestionType())
                ->add('outcomes', new VariedCollectionType(), array(
                    'type_cb' => $typeCb,
                    'type' => new AnswerOutcomeType(),
                    'allow_add'    => true,
                    'allow_delete'    => true,
                ));
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'La\CoreBundle\Entity\Action',
            'csrf_protection'   => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'form_action';
    }
}
