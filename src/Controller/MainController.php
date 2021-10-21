<?php

namespace App\Controller;

use App\Repository\SubscriptionHistoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    
    /**
     * Access to feed page
     * 
     * @Route("/feed", name="hbt_feed")
     * 
     * @return Response
     */
    public function feed(SubscriptionHistoryRepository $subscriptionHistory): Response
    {
        // can access only if loggued
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        return $this->render('main/feed.html.twig', [
            'user' => $this->getUser(),
            'last_subscription' => $subscriptionHistory->findLastSubscriptionHistory($this->getUser())
        ]);
    }
}
