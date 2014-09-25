<?php
namespace PhactTest;

use PHPUnit_Framework_TestCase as TestCase;
use Phact\Manager;
use Phalcon\Events\Manager as EventsManager;
use StdClass;

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
        return new NodeSimple();
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

        $result = $component->execute('A');

        $this->assertSame($component, $result);
    }

    public function testBefore()
    {
        $component = $this->buildManager();
        $nodeA     = $this->getMock('PhactTest\NodeSimple');
        $nodeB     = $this->getMock('PhactTest\NodeSimple');

        $handler = new StdClass();
        $handler->content = '';

        $nodeA->expects($this->atLeastOnce())
            ->method('onExecute')
            ->will($this->returnCallback(function () use ($handler) {
                $handler->content = $handler->content . 'A';
            }));
        $nodeB->expects($this->atLeastOnce())
            ->method('onBeforeExecute')
            ->will($this->returnCallback(function () use ($handler) {
                $handler->content = $handler->content . 'B';
            }));

        $component
            ->add('A', $nodeA)
            ->add('B', $nodeB);

        $component->execute('A');

        $this->assertEquals('BA', $handler->content);
    }

    public function testAfter()
    {
        $component = $this->buildManager();
        $nodeA     = $this->getMock('PhactTest\NodeSimple');
        $nodeB     = $this->getMock('PhactTest\NodeSimple');

        $handler = new StdClass();
        $handler->content = '';

        $nodeA->expects($this->atLeastOnce())
            ->method('onAfterExecute')
            ->will($this->returnCallback(function () use ($handler) {
                $handler->content = $handler->content . 'A';
            }));
        $nodeB->expects($this->atLeastOnce())
            ->method('onExecute')
            ->will($this->returnCallback(function () use ($handler) {
                $handler->content = $handler->content . 'B';
            }));

        $component
            ->add('A', $nodeA)
            ->add('B', $nodeB);

        $component->execute('A');

        $this->assertEquals('BA', $handler->content);
    }

    public function testMultipleNodes()
    {
        $component = $this->buildManager();
        $nodeA     = $this->getMock('PhactTest\NodeSimple');
        $nodeB     = $this->getMock('PhactTest\NodeSimple');
        $nodeC     = $this->getMock('PhactTest\NodeSimple');
        $nodeD     = $this->getMock('PhactTest\NodeSimple');

        $handler = new StdClass();

        $nodeA->method('onExecute')
            ->will($this->returnCallback(function ($event, $node) use ($handler) {
                $handler->content = $handler->content . 'A';
            }));
        $nodeB->method('onBeforeExecute')
            ->will($this->returnCallback(function ($event, $node) use ($handler) {
                $handler->content = 'B';
            }));
        $nodeC->method('onAfterExecute')
            ->will($this->returnCallback(function ($event, $node) use ($handler) {
                $handler->content = $handler->content . 'C';
            }));

        $component
            ->add('A', $nodeA)
            ->add('B', $nodeB)
            ->add('C', $nodeC);

        $component
            ->execute('A')
            ->execute('A');

        $this->assertEquals('BAC', $handler->content);
    }

    public function testUnknownIdentifier()
    {
        $this->setExpectedException('Phact\Exception');

        $component = $this->buildManager();

        $component->execute('A');
    }

    public function testDuplicateIdentifier()
    {
        $this->setExpectedException('Phact\Exception');

        $component = $this->buildManager();
        $nodeA     = $this->buildNode();
        $nodeB     = $this->buildNode();

        $component
            ->add('A', $nodeA)
            ->add('A', $nodeB);
    }

    public function testUnknownEventsManager()
    {
        $this->setExpectedException('Phact\Exception');

        $component = new Manager();
        $node      = $this->buildNode();

        $component->add('identifier', $node);
    }
}
