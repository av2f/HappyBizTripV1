<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class MainPageController extends AbstractController
{
    
    /**
     * Access to main page
     * 
     * @Route("/main", name="hbt_main")
     * 
     * Can access only if loggued
     * @isGranted("ROLE_USER")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainPageController',
        ]);
    }
}
