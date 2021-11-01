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

        return $this->renderForm('profile/change_password.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Edit the profile
     * 
     * @Route("/profile/{slug}", name="profile_edit", methods={"POST", "GET"})
     *
     * @param User $user
     * @param InterestRepository $qInterests
     * @param InterestTypeRepository $qInterestsType
     * @return Response
     */
    public function editProfile(
        User $user,
        InterestRepository $qInterests,
        InterestTypeRepository $qInterestsType,
        EntityManagerInterface $entityManager,
        Request $request ): Response
    {
        // Authorization managed by voter
        $this->denyAccessUnlessGranted('profile_edit', $user);

        // Create the form
        $form = $this->createForm(EditProfileType::class, $user);

        // Handle the submit
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Handle interests
            // Remove current interests of user
            $interests = $user->getInterests();
            foreach($interests as $interest) {
                $user->removeInterest($interest);
            }

            // update interests with new list
            // if there is at least 1 interest in the list
            if (strlen($form->get('listInterest')->getData()) > 0) {
                $arrayInterests = explode(';', $form->get('listInterest')->getData());
                foreach($arrayInterests as $arrayInterest) {
                    $interest = $qInterests->findOneBy(array('id' => $arrayInterest));
                    $user->addInterest($interest);
                }
            }
            // Update user
            $entityManager->flush();

            return $this->redirectToRoute('profile_edit', ['slug' => $user->getSlug()]);
        }
        
        return $this->renderForm('profile/edit_profile.html.twig', [
            'user' =>$this->getUser(),
            'form' => $form,
            'interests_type' => $qInterestsType->findInterestTypeOrder(),
            'interests_name' => $qInterests->findInterestOrder()
        ]);
    }

    /**
     * Delete the profile
     * 
     * @Route("/profile/{id}/delete", name="profile_delete", methods={"POST"})
     *
     * @param Request $resquest
     * @param User $user
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function deleteProfile(
      Request $request,
      User $user,
      EntityManagerInterface $entityManager): Response
    {
      // Check if user is authorized to delete
      $this->denyAccessUnlessGranted('profile_delete', $user);

      if (!$this->isCsrfTokenValid('delete', $request->request->get('token'))) {
        return $this->redirectToRoute('profile_edit', ['slug' => $user->getSlug()]);
      }

      // Set isDeleted to yes, isActive to no
      $user->setIsDeleted(true);
      $user->setIsActive(false);
      $entityManager->flush();
      // Logout the user
      return $this->redirectToRoute('hbt_logout');
    }
}