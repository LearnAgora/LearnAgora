<?php

namespace La\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Goal
 */
class AgoraGoal extends Goal
{
    /**
     * @var Agora
     */
    private $agora;


    /**
     * Set agora
     *
     * @param Agora $agora
     * @return AgoraGoal
     */
    public function setAgora(Agora $agora = null)
    {
        $this->agora = $agora;

        return $this;
    }

    /**
     * Get agora
     *
     * @return Agora
     */
    public function getAgora()
    {
        return $this->agora;
    }
}
