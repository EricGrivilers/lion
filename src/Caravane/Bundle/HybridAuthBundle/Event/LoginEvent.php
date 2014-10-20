<?php


namespace Caravane\Bundle\HybridAuthBundle\Event;

use Symfony\Component\EventDispatcher\Event;


use Caravane\Bundle\UserBundle\Document\User;

class LoginEvent extends Event
{
    private $provider;
    private $user;

    public function __construct(User $user, $provider)
    {
        $this->user = $user;
        $this->provider = $provider;
    }

    /**
     * @return UserInterface
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return provider
     */
    public function getProvider()
    {
        return $this->provider;
    }
}
