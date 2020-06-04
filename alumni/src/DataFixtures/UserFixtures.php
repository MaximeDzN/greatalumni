<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\UserGroups;
use App\Repository\GroupRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{

    private $passwordEncoder;


    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }


    public function load(ObjectManager $manager)
    {
       
            $user = new User();
            $user->setLogin("SUPERADMIN");
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'SUPERADMIN'));
            $user->setEmail("support@greatalumni.fr");
            $user->setGender(0);
            $user->setName('ADMIN');
            $user->setNickname('ADMIN');
            $user->setPromo("2019");
            $user->setPhoto('avatar.png');
            $user->setDepartment('greatalumni admin');
            $user->setIsConfirmed(0);
            $manager->persist($user);
            $user2 = new User();
            $user2->setLogin("user");
            $user2->setRoles(['ROLE_USER']);
            $user2->setPassword($this->passwordEncoder->encodePassword($user,'user'));
            $user2->setEmail("user@greatalumni.fr");
            $user2->setGender(0);
            $user2->setName('USER');
            $user2->setNickname('USER');
            $user2->setPromo("2019");
            $user2->setPhoto('avatar.png');
            $user2->setDepartment('greatalumni user');
            $user2->setIsConfirmed(0);
            $manager->persist($user2);
        $manager->flush();

    }
}
