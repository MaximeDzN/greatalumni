<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DirectoryController extends AbstractController
{
    /**
     * @Route("/directory", name="directory")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(User::class);

        $users = $repo->findAll();


        return $this->render('directory/index.html.twig', [
            'controller_name' => 'DirectoryController',
            'users' => $users
        ]);
    }

    /**
     * @Route("/directory/{id}", name="showInfos")
     */
    public function showUserInfos($id)
    {
        $repo = $this->getDoctrine()->getRepository(user::class);
        $user =$repo ->find($id);
        return $this->render('directory/showInfos.html.twig', [            
            'user' => $user
        ]);
    }
}
