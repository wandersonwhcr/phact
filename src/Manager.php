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
        if ($this->has($identifier)) {
            throw new Exception("Duplicated Node '$identifier'");
        }
        $this->nodes[$identifier] = $node;
        $this->getEventsManager()->attach('node', $node);
        return $this;
    }

    /**
     * Manager contains Node?
     *
     * @param  string $identifier
     * @return bool
     */
    public function has($identifier)
    {
        return isset($this->nodes[$identifier]);
    }

    /**
     * Capture Node by Identifier
     *
     * @param  string        $identifier
     * @return NodeInterface
     * @throws Exception     Unknown Node
     */
    public function get($identifier)
    {
        if (!$this->has($identifier)) {
            throw new Exception("Unknown Node '$identifier'");
        }
        return $this->nodes[$identifier];
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
    public function execute($identifier)
    {
        $node = $this->get($identifier);

        $eventsManager = $this->getEventsManager();

        $eventsManager->fire('node:onBeforeExecute', $node, $identifier);
        $eventsManager->fire('node:onExecute', $node, $identifier);
        $eventsManager->fire('node:onAfterExecute', $node, $identifier);

        return $this;
    }
}
