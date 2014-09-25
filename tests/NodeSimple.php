<?php
namespace PhactTest;

use Phact\NodeInterface;
use Phalcon\Events\Event;

/**
 * Simple Testing Node
 */
class NodeSimple implements NodeInterface
{
    /**
     * Before Execute Event
     *
     * @param  Event         $event
     * @param  NodeInterface $node
     * @return null
     */
    public function onBeforeExecute(Event $event, NodeInterface $node)
    {
    }

    /**
     * Execute Event
     *
     * @param  Event         $event
     * @param  NodeInterface $node
     * @return null
     */
    public function onExecute(Event $event, NodeInterface $node)
    {
    }

    /**
     * After Execute Event
     *
     * @param  Event         $event
     * @param  NodeInterface $node
     * @return null
     */
    public function onAfterExecute(Event $event, NodeInterface $node)
    {
    }
}
