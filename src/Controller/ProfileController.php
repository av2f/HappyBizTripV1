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
   * if action is deleted, remove the old image in $avatarDir if exists
   * 
   * @Route("/profile/{id}/avatar", name="update_avatar", methods={"POST"})
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

    $MAX_FILE_SIZE = 5242880; // 5Mo Octets max

    if (isset($_POST['_token'])) {
      if ($this->isCsrfTokenValid('token_'.$user->getId(), $_POST['_token'])) {
        
        $avatarDir = $this->getParameter('dir_store_avatar');
        $currentAvatarFile = $user->getAvatar();
        
        if ($_POST['action'] === 'update') {
          // update avatar image if file is not empty
          if (!empty($_FILES['image'])) {
            // if image exceeds allowed size
            if ($_FILES['image']['size'] > $MAX_FILE_SIZE) {
              return new JsonResponse([
                'error' => '4'], 400);
            }
            // UPLOAD_ERR_OK
            if ($_FILES['image']['error'] === 0) {
              // Create new UploadedFile instance
              $fileImg = new UploadedFile($_FILES['image']['tmp_name'], $_FILES['image']['name'], $_FILES['image']['type']);
              // Create target unique file
              $exist = true;
              while ($exist) {
                $fileDest = sha1(uniqid('')) . '.' . $fileImg->guessClientExtension();
                if (!file_exists($avatarDir.$fileDest)) {
                  $exist = false;
                }
              }
              // Move file
              try {
                $fileImg->move($avatarDir, $fileDest);
              } catch (Exception $e) {
                echo($e->getMessage());  
                //A GERE avec UNE REPONSE JSON
                var_dump($e->getMessage());
                die();
              }
                // Optimize image
                $imageOptimizer->resize($avatarDir.$fileDest, 600, 600);
                // Store new image in database
                $user->setAvatar($fileDest);
                $entityManager->flush();
                // Delete Old Image
                if (!empty($currentAvatarFile) && file_exists($avatarDir.$currentAvatarFile)) unlink($avatarDir.$currentAvatarFile);
                // return success response
                return new JsonResponse([
                  'success' => '1'], 200);
            }
            // UPLOAD_ERR_INI_SIZE : size of image exceeds upload_max_filesize
            elseif ($_FILES['image']['error'] === 1) {
              return new JsonResponse([
                'error' => '4'], 400);
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
        elseif ($_POST['action'] === 'delete') {
          // Remove image from database
          $user->setAvatar('');
          $entityManager->flush();
          // delete image from $avatarDir
          if (!empty($currentAvatarFile) && file_exists($avatarDir.$currentAvatarFile)) unlink($avatarDir.$currentAvatarFile);
          // Return success response
          return new JsonResponse([
            'success' => '1'], 200);
        }
        else {
          // Return error
          return new JsonResponse([
            'error' => '2'], 400);
        }
      }
      else {
        // Return error
        return new JsonResponse([
          'error' => '3'], 400);
      }
    }
    else {
      return new JsonResponse([
        'Error' => '3'], 400);
    }
  }
}