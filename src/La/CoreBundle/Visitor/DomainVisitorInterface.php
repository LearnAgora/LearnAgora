<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 12:57
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\Domain;

interface DomainVisitorInterface
{
    /**
     * @param Domain $learningEntity
     **/
    public function visitDomain(Domain $learningEntity);
}
