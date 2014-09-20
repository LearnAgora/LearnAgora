<?php

namespace La\SandboxBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use La\CoreBundle\Entity\LearningEntity;
use La\LearnodexBundle\Model\Card;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CardController extends Controller
{
    /**
     * @Rest\View
     */
    public function randomAction()
    {
        $em = $this->getDoctrine()->getManager();

        //whoaa .. for now it works but i should find a way to get a random action
        $learningEntities = $em->getRepository('LaCoreBundle:Action')->findAll();
        if (count($learningEntities)) {
            shuffle($learningEntities);

            /** @var $learningEntity LearningEntity */
            $learningEntity = $learningEntities[0];
            $card = new Card($learningEntity);

            return array('card' => $card);
        }

        return array('test' => 'grrr');
    }
}
