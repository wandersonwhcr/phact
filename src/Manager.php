<?php
namespace Phact;

use Phalcon\Events\EventsAwareInterface;
use Phalcon\Events\Manager as EventsManager;

/**
 * Phact Event Manager
 */
class Manager implements EventsAwareInterface
{
    /**
     * Events Manager
     * @var EventsManager
     */
    private $eventsManager;

    /**
     * Nodes
     * @var NodeInterface[]
     */
    private $nodes = array();

    /**
     * Set Events Manager
     *
     * @param  EventsManager $eventsManager Element
     * @return Manager       Fluent Interface
     */
    public function setEventsManager($eventsManager)
    {
        $this->eventsManager = $eventsManager;
        return $this;
    }

    /**
     * Get Events Manager
     *
     * @return EventsManager Element
     */
    public function getEventsManager()
    {
        return $this->eventsManager;
    }

    /**
     * Add a Node
     *
     * @param  string        $identifier Identifier
     * @param  NodeInterface $node       Element
     * @return Manager       Fluent Interface
     */
    public function add($identifier, NodeInterface $node)
    {
        $this->nodes[$identifier] = $node;
        return $this;
    }

    /**
     * Show Nodes
     *
     * @return NodeInterface[] Nodes
     */
    public function getNodes()
    {
        return $this->nodes;
    }

    /**
     * Execute
     *
     * @param  string  $identifier Identifier
     * @return Manager Fluent Interface
     */
    public function execute($name)
    {
        $node = $this->nodes[$name];

        $eventsManager = $this->getEventsManager();

        $eventsManager->fire('node:onBeforeExecute', $node);
        $eventsManager->fire('node:onExecute', $node);
        $eventsManager->fire('node:onAfterExecute', $node);

        return $this;
    }
}
