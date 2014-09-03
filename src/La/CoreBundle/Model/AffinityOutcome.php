<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/3/14
 * Time: 10:30 AM
 */

namespace La\CoreBundle\Model;

use La\CoreBundle\Entity\Outcome;

class AffinityOutcome extends Outcome{
    public function __construct() {
        $this->setSubject('Affinity');
    }
}