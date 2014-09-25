<?php
namespace Phact;

/**
 * Phact Event Manager
 */
class Manager
{
    /**
     * Nodes
     * @var NodeInterface[]
     */
    private $nodes = array();

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
}
