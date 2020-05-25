<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\PostType;
use App\Entity\Post;
use App\Entity\PostAnswer;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PostTypeRepository;
use App\Repository\PostRepository;
use App\Repository\PostAnswerRepository;

use App\Entity\Comment;
use App\Entity\Score;
use App\Form\CommentType;
use App\Form\ScoreType;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;
use App\Repository\ScoreRepository;

class ForumController extends AbstractController
{
    /**
    * @Route("/forum", name="forum")
    */
    public function GetForum(PostTypeRepository $PostTypeRepo)
    {
        $allPostType = $PostTypeRepo->findAll();

        return $this->render('forum/forum.html.twig', [
            'postType' => $allPostType
        ]);
    }

    /**
    * @Route("/forum/{category}", name="forumCategory")
    */
    public function GetForumCategory(PostRepository $PostRepo)
    {

        $allPost = $PostRepo->findAllReverse();
        $user = $this->getUser();
        
        return $this->render('forum/forumCategory.html.twig', [
            'post' => $allPost

        ]);
    }

    /**
    * @Route("/forum/{category}/thread/{id}", name="forumThread")
    */
    public function GetForumThread(PostAnswerRepository $PostAnswerRepo)
    {
        $allPostAnswer = $PostAnswerRepo->findAll();

        return $this->render('forum/forumThread.html.twig', [
            'postAnswer' => $allPostAnswer
        ]);
    }

    






}
