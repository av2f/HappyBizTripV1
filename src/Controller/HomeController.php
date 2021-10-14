<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\HomeRegisterType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * Home page
     * 
     * @Route("/", name="homepage", methods={"POST"})
     *
     * @return Response
     */
    public function index(): Response
    {
        $user = new User;
        $form = $this->createForm(HomeRegisterType::class, $user);
        
        return $this->renderForm('home/index.html.twig', [
            'form' => $form
        ]);
    }
}
