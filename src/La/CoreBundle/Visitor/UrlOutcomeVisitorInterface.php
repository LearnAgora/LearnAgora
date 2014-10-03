<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 13:59
 */

namespace La\CoreBundle\Visitor;


use La\CoreBundle\Entity\UrlOutcome;

interface UrlOutcomeVisitorInterface {
    /**
     * @param UrlOutcome $outcome
     **/
    public function visitUrlOutcome(UrlOutcome $outcome);
} 