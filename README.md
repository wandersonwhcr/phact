# Phact

Phact is a simple event propagation usage with Phalcon Framework. With Phact you can create a set of nodes, write some events and execute them with some order.

## Example

```php
use Phact\Manager;
use Phact\NodeInterface;
use Phalcon\Events\Event;
use Phalcon\Events\Manager as EventsManager;

class A implements NodeInterface
{
    public function onExecute(Event $event, NodeInterface $node)
    {
        if ($node instanceof self) { // or $event->getData() == 'A'
            echo "A!";
        }
    }
}

class B implements NodeInterface
{
    public function onBeforeExecute(Event $event, NodeInterface $node)
    {
        if ($node instanceof A) { // or $event->getData() == 'A'
            echo "B before A! ";
        }
    }
}

$manager = (new Manager())
    ->setEventsManager(new EventsManager())
    ->add('A', new A())
    ->add('B', new B());

$manager->execute('A'); // outputs "B before A! A!"
```

## Composer

You can install ```Phact``` inside your project with Composer.

```json
{
    "require": {
        "wandersonwhcr/phact": "1.*"
    }
}
```

## Usage

You can implement three methods using ```NodeInterface```:

```php
use Phact\NodeInterface;
use Phalcon\Events\Event;

class Node implements NodeInterface
{
    public function onBeforeExecute(Event $event, NodeInterface $node)
    {
    }
    
    public function onExecute(Event $event, NodeInterface $node)
    {
    }
    
    public function onAfterExecute(Event $event, NodeInterface $node)
    {
    }
}
```

With these events, you can order your node executions and create awesome events propagation like installers or database structure changes.
