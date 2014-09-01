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


interface ParticleVisitorInterface {

    /**
     * @param Agora $particle
     **/
    public function visitAgora(Agora $particle);

    /**
     * @param Objective $particle
     **/
    public function visitObjective(Objective $particle);

    /**
     * @param Action $particle
     **/
    public function visitAction(Action $particle);

}