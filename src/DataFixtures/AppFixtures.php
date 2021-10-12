<?php
/**
 * BE CAREFUL : before performing fixtures, comment the line
 * in User entity => function setInitialUser()
 * $this->setIsSubscribed(false);
 * AND
 * in Messaging entity => function setInitialMessaging()
 * $this->createdAt = new \DateTime();
 * $this->setIsReaded(false);
 */

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    
    public function load(ObjectManager $manager)
    {
        $NB_USER = 20;
        $faker = Faker\Factory::create('fr_FR');
        // Define genders (W=Woman / M=Man)
        $genders = array('W', 'M');
        // Define situation (C=Couple / S=Single / K=Keep myself)
        $situations = array('C', 'S', 'K');
        // Define if subscribed
        $subscribes = array(true, false);

        for ($i=0; $i<=$NB_USER; $i++) {
            $user = new User();
            
            // Generate randomly if subscribed or not
            $subscribed = $subscribes[mt_rand(0, count($subscribes)-1)];

            // Generate randomly situation
            $situation = $situations[mt_rand(0, count($situations)-1)];

            // Generate ramdonly the gender
            $gender = $genders[mt_rand(0, count($genders)-1)];

            // generate firstname and avatar following the gender
            $firstName = ($gender == 'M' ? 
                $faker->firstNameMale : $faker->firstNameFemale);

            // Generate a picture
            $picture = 'https://randomuser.me/api/portraits/';
            $pictureId = $faker->numberbetween(1, 99) . '.jpg';
            $picture .= ($gender=='M' ? 'men/' : 'women/') .$pictureId;
            
            // define a birthday date
            $birthDate = new \DateTimeImmutable();
            $birthDate = $faker->dateTimeBetween('-60 years', '-20 years');

            // Create new user
            // CreatedAt is generated with prePersist function
            $user->setEmail($faker->email)
                ->setPassword(
                    $this->passwordHasher->hashPassword(
                        $user,
                        'password'
                    )
                )
                ->setGender($gender)
                ->setFirstName($firstName)
                ->setLastName($faker->lastName)
                ->setSlug(strtolower($firstName))
                ->setBirthDate($birthDate)
                ->setSituation($situation)
                ->setAvatar($picture)
                ->setProfession($faker->jobTitle)
                ->setCompany($faker->company)
                ->setDescription($faker->sentence())
                ->setIsSubscribed($subscribed);

            $manager->persist($user);
        }
        $manager->flush();      
    }
}
