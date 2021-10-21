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
use App\Entity\SubscriptionType;
use App\Entity\SubscriptionHistory;
use DateInterval;
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
        
        // Subscription Type
        $subType = array(
            ['subscribName' => 'subscription.type.occasional', 'duration' => 1, 'durationType' => 'W', 'price' => 8.99],
            ['subscribName' => 'subscription.type.punctual', 'duration' => 1, 'durationType' => 'M', 'price' => 28.99],
            ['subscribName' => 'subscription.type.temporary', 'duration' => 3, 'durationType' => 'M', 'price' => 75.52],
            ['subscribName' => 'subscription.type.regular', 'duration' => 6, 'durationType' => 'M', 'price' => 129.46],
            ['subscribName' => 'subscription.type.permanent', 'duration' => 12, 'durationType' => 'M', 'price' => 215.76]
        );
        $subscriptionTypeArray=[];
        foreach($subType as $nb => $infos) {
            $subscripType = new SubscriptionType();
            $subscripType->setSubscribName($infos['subscribName']);
            $subscripType->setDuration($infos['duration']);
            $subscripType->setDurationType($infos['durationType']);
            $subscripType->setPrice($infos['price']);
            $manager->persist($subscripType);
            $subscriptionTypeArray[]=$subscripType;
        }
        $manager->flush();
        
        // Users
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

            // Manage subscription
            if ($subscribed) {
                // if isSubscribed = true
                $dateBegin = new \DateTime();
                $subscription = $subscriptionTypeArray[mt_rand(0, count($subscriptionTypeArray)-1)];
                switch ($subscription->getDurationType()) {
                    case 'W':
                        $dateBegin = $faker->dateTimeBetween('-6 days', '-1 day');
                        break;
                    case 'M':
                        switch ($subscription->getDuration()) {
                            case 1:
                                $dateBegin = $faker->dateTimeBetween('-20 days', '-3 days');
                                break;
                            case 3:
                                $dateBegin = $faker->dateTimeBetween('-2 months', '-5 days');
                                break;
                            case 6:
                                $dateBegin = $faker->dateTimeBetween('-4 months', '-1 month');
                                break;
                            case 12:
                                $dateBegin = $faker->dateTimeBetween('-18 months', '-28 days');
                                break;
                        
                        }
                        break;
                }
                $dateEnd = clone $dateBegin;
                $interval = "P".$subscription->getDuration().$subscription->getDurationType();
                $dateEnd->add(new DateInterval($interval));
                
                $user   ->setSubscribPayAt(\DateTimeImmutable::createFromMutable($dateBegin))
                        ->setSubscribBeginAt(\DateTimeImmutable::createFromMutable($dateBegin))
                        ->setSubscribEndAt(\DateTimeImmutable::createFromMutable($dateEnd))
                        ->setSubscriptionType($subscription);
            }
            $manager->persist($user);

            // Create subscription history
            if ($subscribed) {
                $j = mt_rand(1,5);
                for ($u=0; $u<=$j; $u++) {
                    $dateBegin = new \DateTime();
                    $subscription = $subscriptionTypeArray[mt_rand(0, count($subscriptionTypeArray)-1)];
                    switch ($subscription->getDurationType()) {
                        case 'W':
                            $dateBegin = $faker->dateTimeBetween('-360 days', '-9 days');
                            break;
                        case 'M':
                            switch ($subscription->getDuration()) {
                                case 1:
                                    $dateBegin = $faker->dateTimeBetween('-24 months', '-1 month');
                                    break;
                                case 3:
                                    $dateBegin=$faker->dateTimeBetween('-36 months','-3 months');
                                    break;
                                case 6:
                                    $dateBegin=$faker->dateTimeBetween('-60 months','-6 months');
                                    break;
                                case 12:
                                    $dateBegin=$faker->dateTimeBetween('-48 months','-12 months');
                                    break;
                            }
                        break;
                    }
                    $dateEnd = clone $dateBegin;
                    $interval = "P".$subscription->getDuration().$subscription->getDurationType();
                    $dateEnd->add(new DateInterval($interval));
                    $subscribe = new SubscriptionHistory();
                    $subscribe  -> setSubscriber($user)
                                -> setSubscriptionType($subscription)
                                ->setSubscribPayAt(\DateTimeImmutable::createFromMutable($dateBegin))
                                ->setSubscribBeginAt(\DateTimeImmutable::createFromMutable($dateBegin))
                                ->setSubscribEndAt(\DateTimeImmutable::createFromMutable($dateEnd));
                    $manager->persist($subscribe);
                }
            }
        }

        $manager->flush();
    }
}
