<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 8/29/14
 * Time: 11:20 PM
 */

namespace La\CoreBundle\Model;

use La\CoreBundle\Entity\Agora;
use La\CoreBundle\Entity\Objective;
use La\CoreBundle\Entity\Action;


interface LearningEntityVisitorInterface {

    /**
     * @param Agora $learningEntity
     **/
    public function visitAgora(Agora $learningEntity);

    /**
     * @param Objective $learningEntity
     **/
    public function visitObjective(Objective $learningEntity);

    /**
     * @param Action $learningEntity
     **/
    public function visitAction(Action $learningEntity);

}