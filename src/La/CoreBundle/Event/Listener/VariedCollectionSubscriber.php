<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 10.07.15
 * Time: 11:00
 */

namespace La\CoreBundle\Event\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormFactoryInterface;

class VariedCollectionSubscriber implements EventSubscriberInterface
{
    protected $factory;
    protected $type;
    protected $typeCb;
    protected $options;

    public function __construct(FormFactoryInterface $factory, $type, $typeCb)
    {
        $this->factory = $factory;
        $this->type = $type;
        $this->typeCb = $typeCb;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'fixChildTypes'
        );
    }

    public function fixChildTypes(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();

        // Go with defaults if we have no data
        if($data === null || '' === $data)
        {
            return;
        }
        // It's possible to use array access/addChild, but it's not a part of the interface
        // Instead, we have to remove all children and re-add them to maintain the order
        $toAdd = array();
        foreach($form as $name => $child)
        {
            // Store our own copy of the original form order, in case any are missing from the data
            $toAdd[$name] = $child->getConfig()->getOptions();
            $form->remove($name);
        }

        // Now that the form is empty, build it up again
        foreach($toAdd as $name => $origOptions)
        {
            // Decide whether to use the default form type or some extension
            $datum = $data[$name] ?: null;
            $type = $this->type;
            if($datum)
            {
                $calculatedType = call_user_func($this->typeCb, $datum);
                if($calculatedType)
                {
                    $type = $calculatedType;
                }
            }
            // And recreate the form field
            if ($name == "2") {
                $origOptions['data_class'] = 'La\CoreBundle\Entity\UrlOutcome';
            } else {
                $origOptions['data_class'] = 'La\CoreBundle\Entity\ButtonOutcome';
            }
            $form->add($this->factory->createNamed($name, $type, null, $origOptions));
        }
    }
}