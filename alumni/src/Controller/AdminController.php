<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\News;
use App\Entity\Comment;

use App\Repository\CommentRepository;
use App\Repository\NewsRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(EntityManagerInterface $manager, Request $request,CommentRepository $repoCom )
    {
     
        $reportedComment = $repoCom->findBy(['IsReported' => true]);
      

       
         return $this->render('admin/index.html.twig', [
            'reportedComment' => $reportedComment
        ]); 
    }

     /**
     * @Route("/admin/comdel/{id}", name="admin_comment_del")
     */
    public function commentDel(EntityManagerInterface $manager, Request $request, CommentRepository $comRepo, Comment $comment)
    {
        $user = $this->getUser();
        //On regarde qui est l'auteur du commentaire ou si l'utilisateur est administrateur
        if ($user == $comment->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles())) {
            $news = $comment->getNews();
            //On supprime le commentaire
            $comRepo->deleteOne($comment->getId());
            //On redirige sur l'article.
            return $this->redirectToRoute('admin');
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

}
