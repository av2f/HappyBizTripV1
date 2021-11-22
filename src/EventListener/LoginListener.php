<?php

namespace App\EventListener;

/**
 * Handle during the login :
 * - if user is subscribed and end of subscription <=15 days, show a notification
 * - Store in database the datetime of login
 */
  

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class LoginListener
{ 
  private $flashbag;
  private $entityInterface;
  
  public function __construct(FlashBagInterface $flashbag, EntityManagerInterface $entityInterface)
  {
    $this->flashbag = $flashbag;
    $this->entityInterface = $entityInterface;
  }
  
  public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
  {
    // Get the user entity
    $user = $event->getAuthenticationToken()->getUser();

    // store datetime of login
    $user->setLastLogin(new \DateTime());
    $this->entityInterface->flush();

    // if end of subscription <= 15 days, show message on login
    if ($user->getIsSubscribed() && $user->getDaysEndOfSubscription() <= 15) {
      $this->flashbag->add('warning', 'subscription');
    }
  }
}