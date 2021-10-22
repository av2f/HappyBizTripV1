<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\EditProfileType;
use App\Repository\InterestRepository;
use App\Repository\InterestTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(): Response
    {
        return $this->render('profile/index.html.twig', [
            'controller_name' => 'ProfileController',
        ]);
    }


    /**
     * Manage the form to change password
     *
     * @Route("/profile/password/{slug}", name="profile_password", methods={"POST", "GET"})
     * 
     * @param Request $request
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function changePassword(
        Request $request,
        User $user,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher): Response
    {
        // Authorization managed by voter
        $this->denyAccessUnlessGranted('profile_edit', $user);

        // create form
        $form = $this->createForm(ChangePasswordType::class);

        //handle the form
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // hash the Password (based on the security.yaml config)
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('newPassword')->getData()
            );
            $user->setPassword($hashedPassword);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'update.password.successfull'
            );

            return $this->redirectToRoute('hbt_feed');
        }

        return $this->renderForm('profile/changePassword.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Edit the profile
     * 
     * @Route("/profile/{slug}", name="profile_edit", methods={"POST", "GET"})
     *
     * @param User $user
     * @return Response
     */
    public function editProfile(
        User $user,
        InterestRepository $qInterests,
        InterestTypeRepository $qInterestsType,
        Request $resquest ): Response
    {
        // Authorization managed by voter
        $this->denyAccessUnlessGranted('profile_edit', $user);

        // Create the form
        $form = $this->createForm(EditProfileType::class, $user);
        
        return $this->renderForm('profile/editProfile.html.twig', [
            'user' =>$this->getUser(),
            'form' => $form,
            'interests_type' => $qInterestsType->findInterestTypeOrder(),
            'interests_name' => $qInterests->findInterestOrder()
        ]);
    }
}