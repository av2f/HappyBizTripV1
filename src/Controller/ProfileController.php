<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\EditProfileType;
use App\Repository\InterestRepository;
use App\Repository\InterestTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Service\ImageOptimizer;
use Exception;
use phpDocumentor\Reflection\DocBlock\Tags\Var_;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
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

  /**
   * Update the image of avatar
   * Store the new image in $avatarDir
   * Update in database
   * Remove the old image in $avatarDir if exists
   * 
   * @Route("/profile/{id}/updavatar", name="update_avatar", methods={"POST"})
   *
   * @param User $user
   * @param EntityManagerInterface $entityManager
   * @param ImageOptimizer $ImageOptimize
   * @return void
   */
  function updateAvatar(
    User $user,
    EntityManagerInterface $entityManager,
    ImageOptimizer $imageOptimizer)
  {
    // Authorization managed by voter
    // Check if user is authorized to update
    $this->denyAccessUnlessGranted('profile_edit', $user);

    $MAX_FILE_SIZE = 6291456; // 6Mo for file uploaded 
    $MAX_FILE_SIZE_OPTIMIZED = 3145728; // 3Mo Octets max after optimized

    if (isset($_POST['_token']) && $this->isCsrfTokenValid('token_'.$user->getId(), $_POST['_token'])) {
      // UPLOAD_ERR_INI_SIZE : size of image exceeds upload_max_filesize
      // or file size is upper than $MAX_FILE_SIZE
      if ($_FILES['image']['error'] === 1 || $_FILES['image']['size'] > $MAX_FILE_SIZE) {
        return new JsonResponse([
          'error' => '3'], 400);
      }
      // UPLOAD_ERR_OK
      elseif (!empty($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $avatarDir = $this ->getParameter('dir_store_avatar');
        $currentAvatarFile = $user->getAvatar();
        // Create new UploadedFile instance
        $fileImg = new UploadedFile($_FILES['image']['tmp_name'], $_FILES['image']['name'], $_FILES['image']['type']);
        // Create target unique file
        $boolExist = true;
        while ($boolExist) {
          $fileDest = sha1(uniqid('')) . '.' . $fileImg->guessClientExtension();
          if (!file_exists($avatarDir.$fileDest)) {
            $boolExist = false;
          }
        }
        // Move file
        try {
          $fileImg->move($avatarDir, $fileDest);
        } catch (Exception $e) {
          // return error
          return new JsonResponse([
            'error' => '2'], 400);
        }
        // Optimize image
        $imageOptimizer->resize($avatarDir.$fileDest, 600, 600);
        // if size of image is more than $MAX_FILE_SIZE_OPTIMIZED
        // return error message and delete the file
        if (filesize($avatarDir.$fileDest) > $MAX_FILE_SIZE_OPTIMIZED) {
          unlink($avatarDir.$fileDest);
          return new JsonResponse([
            'error' => '3'], 400);
        } 
        // Store new image in database
        $user->setAvatar($fileDest);
        $entityManager->flush();
        // Delete Old Image
        if (!empty($currentAvatarFile) && file_exists($avatarDir.$currentAvatarFile)) unlink($avatarDir.$currentAvatarFile);
        // return success response
        return new JsonResponse([
          'success' => $fileDest], 200);
      }
      else {
        // return error
        return new JsonResponse([
          'error' => '2'], 400);
      }
    }
    else {
      // return error
      return new JsonResponse([
        'error' => '2'], 400);
    }
  }

  /**
   * Delete the image of avatar
   * Update in database
   * Remove the old image in $avatarDir if exists
   * 
   * @Route("/profile/{id}/delavatar", name="delete_avatar", methods={"POST"})
   *
   * @param User $user
   * @param EntityManagerInterface $entityManager
   * @return void
   */
  function deleteAvatar(
    User $user, EntityManagerInterface $entityManager
    )
  {
    // Authorization managed by voter
    // Check if user is authorized to update
    $this->denyAccessUnlessGranted('profile_edit', $user);
    if (isset($_POST['_token']) && $this->isCsrfTokenValid('token_'.$user->getId(), $_POST['_token'])) {
      $avatarDir = $this ->getParameter('dir_store_avatar');
      $currentAvatarFile = $user->getAvatar();
      // remove old image from database
      $user->setAvatar('');
      $entityManager->flush();
      // delete old avatar
      if (!empty($currentAvatarFile) && file_exists($avatarDir.$currentAvatarFile)) unlink($avatarDir.$currentAvatarFile);
      // return success response
      return new JsonResponse([
          'success' => '1'], 200);
    } else {
      // Return error
      return new JsonResponse([
        'error' => '2'], 400
      );
    }
  }
}