<?php  

namespace App\DataFixtures;

use App\Entity\PostType;
use App\Entity\Post;
use App\Entity\PostAnswer;
use App\Entity\User;

use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;

class ForumFixtures extends Fixture implements DependentFixtureInterface
{
    private $userRepository;

    public function __construct( UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        

        for ($i = 0; $i <= 10; $i++) {
            $postType = new PostType();
            $postType->setTitle($faker->word(2, true));
            $postType->setDescription($faker->sentences(3, true));
            $manager->persist($postType);

            for ($j = 0; $j <= 20; $j++) {
                $user = $this->getReference('user-' . mt_rand(0, 20));
                
                $post = new Post();
                $post->setTitle($faker->word(2, true));
                $post->setContent($faker->sentences(3, true));
                $post->setPostType($postType);
                $post->setDate(new \DateTime('now'));
                $post->setAuthor($user);
                

                $manager->persist($post);

                for ($k = 0; $k < 20; $k++) {
                    $user = $this->getReference('user-' . mt_rand(0, 20));
                   

                    $postAnswer = new PostAnswer();
                    $postAnswer->setContent($faker->paragraph($nbSentences = 20));
                    $postAnswer->setPost($post);
                    $postAnswer->setPost($post);
                    $postAnswer->setDate(new \DateTime('now'));
                    $postAnswer->setAuthor($user);
                    
                    
                    $manager->persist($postAnswer);
                }
               
            }
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
