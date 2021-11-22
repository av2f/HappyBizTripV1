<?php

/* Service to calculate and update
the percentage of information completed
for the profile.
Author : Frederic Parmentier
*/

namespace App\Service;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ComputeCompleted 
{
  private $entityInterface;

  public function __construct(EntityManagerInterface $entityInterface)
  {
    $this->entityInterface = $entityInterface;
  }

  public function updateCompleted(User $user)
  {
    $userInfo = $user->getUserInfo();
    $TOTAL_USER_OBJECT = 12;
    // by default, 3 objects fullfiled {firstName/email/Date of Birth}
    // object password not taken into account
    $userObjectCompleted=3;
    $user->getLastName() != "" ? $userObjectCompleted++ : "" ;
    $user->getAvatar() != "" ? $userObjectCompleted++ : "" ;
    if ($user->getUserInfo()) {
      $userInfo->getGender() != "" ? $userObjectCompleted++ : "";
      $userInfo->getSituation() != "" ? $userObjectCompleted++ : "" ;
      $userInfo->getProfession() != "" ? $userObjectCompleted++ : "" ;
      $userInfo->getCompany() != "" ? $userObjectCompleted++ : "" ;
      $userInfo->getDescription() != "" ? $userObjectCompleted++ : "" ;
      $userInfo->getPhoneNumber() != "" ? $userObjectCompleted++ : "" ;
    }
    count($user->getInterests()) != 0 ? $userObjectCompleted++ : "";
    $user->setCompleted(round(($userObjectCompleted*100)/$TOTAL_USER_OBJECT));
    $this->entityInterface->flush();
  }
}