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
        for($i = 1; $i<=10 ; $i++)
        {
            $user = new User();
            $user->setLogin("SUPERADMIN $i");
            $user->setRoles(['ROLE_ADMIN']);
            $user->setPassword($this->passwordEncoder->encodePassword($user,'SUPERADMIN'));
            $user->setEmail("support$i@greatalumni.fr");
            $user->setGender(0);
            $user->setName('ADMIN');
            $user->setNickname('ADMIN');
            $user->setPromo("$i");
            $user->setDepartment('greatalumni admin');
            $user->setIsConfirmed(0);
            $manager->persist($user);
        }
        $manager->flush();

    }
}
