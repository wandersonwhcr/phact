<?php
namespace PhactTest;

use PHPUnit_Framework_TestCase as TestCase;
use Phact\Manager;
use Phalcon\Events\Manager as EventsManager;

class ManagerTest extends TestCase
{
    protected function buildManager()
    {
        $manager = (new Manager())
            ->setEventsManager(new EventsManager());
        return $manager;
    }

    protected function buildNode()
    {
        return $this->getMock('Phact\NodeInterface');
    }

    public function testConstructor()
    {
        $manager = $this->buildManager();

        $this->assertInstanceOf('Phalcon\Events\EventsAwareInterface', $manager);
    }

    public function testAdd()
    {
        $component = $this->buildManager();
        $node      = $this->buildNode();

        $result = $component->add('identifier', $node);
        $this->assertSame($component, $result);

        $result = $component->getNodes();
        $this->assertEquals(array('identifier' => $node), $result);
    }

    public function testExecute()
    {
        $component = $this->buildManager();
        $node      = $this->buildNode();

        $component->add('A', $node);

        $component->execute('A');
    }
}
