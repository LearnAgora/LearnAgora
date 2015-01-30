<?php

namespace La\CoreBundle\Tests\Event\Listener;

use La\CoreBundle\Event\Listener\GoalKeeper;
use Prophecy\PhpUnit\ProphecyTestCase;

class GoalKeeperTest extends ProphecyTestCase
{
    private $goalManager;

    /**
     * @var GoalKeeper
     */
    private $sut;

    protected function setUp()
    {
        parent::setUp();

        $this->goalManager = $this->prophesize('\La\CoreBundle\Model\Goal\GoalManager');
        $this->sut = new GoalKeeper($this->goalManager->reveal());
    }

    /** @test */
    public function it_sets_goal_details_on_goal_update()
    {
        $goal = $this->prophesize('La\CoreBundle\Entity\Goal');
        $event = $this->prophesize('La\CoreBundle\Event\GoalEvent');
        $event->getGoal()->willReturn($goal->reveal());

        $this->goalManager->setGoal($goal->reveal())->shouldBeCalled();

        $this->sut->onGoalUpdate($event->reveal());
    }

    /** @test */
    public function it_updates_goal_details_on_personaMatch_update()
    {
        $personaMatch = $this->prophesize('La\CoreBundle\Entity\PersonaMatch');
        $event = $this->prophesize('La\CoreBundle\Event\PersonaMatchEvent');
        $event->getPersonaMatch()->willReturn($personaMatch->reveal());

        $this->goalManager->updateGoal($personaMatch->reveal())->shouldBeCalled();

        $this->sut->onPersonaMatchUpdate($event->reveal());
    }
}
