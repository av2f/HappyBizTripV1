<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\HomeRegisterType;
use App\Security\LoginFormAuthenticator;
use App\Service\ComputeCompleted;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class HomeController extends AbstractController
{
    /**
     * Home page
     * 
     * @Route("/", name="homepage", methods={"POST"})
     *
     * @return Response
     */
    public function index(
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        EntityManagerInterface $entityManager,
        UserAuthenticatorInterface $authenticator,
        ComputeCompleted $computeCompleted,
        LoginFormAuthenticator $LoginFormAuthenticator): Response
    {
        // If already connected
        if ($this->getUser()) {
            return $this->redirectToRoute('hbt_feed');
        }
        
        $user = new User;
        $form = $this->createForm(HomeRegisterType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // hash the Password (based on the security.yaml config)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('password')->getData()
            );

            $user->setPassword($hashedPassword);

            // update the lastLogin field
            $user->setLastLogin(new \DateTime());
            
            // Store the new user
            $entityManager->persist($user);
            $entityManager->flush();
            // handle percentage of completion
            $computeCompleted->updateCompleted($user);
            

            $this->addFlash(
                'success',
                'welcome'
            );

            // if no error, authentication successfully, redirect to main page
            return $authenticator->authenticateUser(
                $user,
                $LoginFormAuthenticator,
                $request
            );
        }
        
        return $this->renderForm('home/index.html.twig', [
            'form' => $form
        ]);
    }
}
