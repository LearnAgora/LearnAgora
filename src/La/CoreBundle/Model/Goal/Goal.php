<?php
/**
 * Created by PhpStorm.
 * User: bart
 * Date: 9/2/14
 * Time: 7:02 PM
 */

namespace La\CoreBundle\Model\Goal;

use JMS\DiExtraBundle\Annotation as DI;
use La\CoreBundle\Visitor\VisitorInterface;
use La\LearnodexBundle\Model\Visitor\Goal\GetAffinityVisitor;
use La\LearnodexBundle\Model\Visitor\Goal\GetNameVisitor;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * @DI\Service("la_core.goal")
 */

abstract class Goal
{
    /**
     * @var SessionInterface
     *
     */
    private $session;
    /**
     * @var GetAffinityVisitor
     */
    private $getAffinityVisitor;

    /**
     * Constructor.
     *
     * @param SessionInterface $session
     * @param GetAffinityVisitor $getAffinityVisitor
     *
     * @DI\InjectParams({
     *  "session" = @DI\Inject("session"),
     *  "getAffinityVisitor" = @DI\Inject("la_core.get_affinity_visitor")
     * })
     */

    public function __construct(SessionInterface $session, GetAffinityVisitor $getAffinityVisitor)
    {

        $this->session = $session;
        $this->getAffinityVisitor = $getAffinityVisitor;

    }

    abstract public function accept(VisitorInterface $visitor);

    public function getName() {
        $getNameVisitor = new GetNameVisitor();
        return $this->accept($getNameVisitor);
    }

    public function getAffinity() {
        //return $this->accept($this->getAffinityVisitor);
        return 5;
    }

}
