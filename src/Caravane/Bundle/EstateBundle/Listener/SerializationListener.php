<?php
namespace Caravane\Bundle\EstateBundle\Listener;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use JMS\DiExtraBundle\Annotation\Inject;
use JMS\DiExtraBundle\Annotation\InjectParams;
use Symfony\Component\HttpKernel\Event\PostResponseEvent;

use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\EventDispatcher\EventSubscriberInterface;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;
use JMS\Serializer\JsonSerializationVisitor;


use Caravane\Bundle\EstateBundle\Entity\Estate;

/**
 * Add data after serialization
 *
 * @Service("caravane.listener.serialization_listener")
 * @Tag("jms_serializer.event_subscriber")
 */
class SerializationListener implements EventSubscriberInterface
{

   
    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return array(
            array('event' => 'serializer.pre_serialize', 'class' => 'Caravane\Bundle\EstateBundle\Entity\Estate', 'method' => 'onPreSerialize'),
            array('event' => 'serializer.post_serialize', 'class' => 'Caravane\Bundle\EstateBundle\Entity\Estate', 'method' => 'onPostSerialize'),
        );
    }

    public function onPreSerialize(PreSerializeEvent $event)
    {
        
        $entity = $event->getObject();
        $event->getVisitor()->addData('defaultPict');
    }

    public function onPostSerialize(ObjectEvent $event)
    {
        $event->getVisitor()->addData('defaultPict','someValue');
    }
}

