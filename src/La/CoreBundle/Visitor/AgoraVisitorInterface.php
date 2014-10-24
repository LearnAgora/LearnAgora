<?php
/**
 * Created by PhpStorm.
 * User: Mihai
 * Date: 07/09/14
 * Time: 12:57
 */

namespace La\CoreBundle\Visitor;

use La\CoreBundle\Entity\Agora;

interface AgoraVisitorInterface
{
    /**
     * @param Agora $learningEntity
     **/
    public function visitAgora(Agora $learningEntity);
}
