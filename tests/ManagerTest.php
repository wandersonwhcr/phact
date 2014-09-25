<?php
namespace PhactTest;

use PHPUnit_Framework_TestCase as TestCase;
use Phact\Manager;

class ManagerTest extends TestCase
{
    protected function buildManager()
    {
        return new Manager();
    }

    protected function buildNode()
    {
        return $this->getMock('Phact\NodeInterface');
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
}
