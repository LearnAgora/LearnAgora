<?php

namespace La\LearnodexBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use La\CoreBundle\Entity\User;

class DefaultController extends Controller
{
    public function indexAction()
    {
        /** @var $user User */
        $user = $this->get('security.context')->getToken()->getUser();

        $learningEntities = $user->getLearningEntities();

        //sort learningEntities per class, i guess there are better patterns for this
        $agoras = array();
        $objectives = array();
        $actions = array();
        foreach ($learningEntities as $learningEntity) {
            if (is_a($learningEntity,'La\CoreBundle\Entity\Agora')) {
                $agoras[] = $learningEntity;
            }
            if (is_a($learningEntity,'La\CoreBundle\Entity\Objective')) {
                $objectives[] = $learningEntity;
            }
            if (is_a($learningEntity,'La\CoreBundle\Entity\Action')) {
                $actions[] = $learningEntity;
            }
        }

        return $this->render('LaLearnodexBundle:Default:index.html.twig', array(
            'userName'   => $user->getUserName(),
            'agoras'     => $agoras,
            'objectives' => $objectives,
            'actions'    => $actions,
        ));
    }
}
