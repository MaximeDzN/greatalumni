<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i=0; $i <5 ; $i++) { 
            $news = new News();
            $news->setTitle("Titre $i");

            $manager->persist($news);
            
        }
        $manager->flush();




    }
}
