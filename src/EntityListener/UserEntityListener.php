<?php

namespace App\EntityListener;

use App\Entity\User;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class UserEntityListener
{
    private $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger=$slugger;
    }

    /**
     * Generate the slug under format firstName-id
     * when profile is created
     *
     * @param User $user
     * @param LifeCycleEventArgs $event
     * @return void
     */
    public function postPersist(User $user, LifeCycleEventArgs $event)
    {
        $user->defineSlug($this->slugger);
        $event->getObjectManager()->flush();
    }
    
    /**
     * Generate the slug under format firstName-id
     * when profile is updated
     *
     * @param User $user
     * @return void
     */
    public function preUpdate(User $user)
    {
        $user->defineSlug($this->slugger);
    }
}