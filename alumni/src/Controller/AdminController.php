<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\News;

use App\Repository\CommentRepository;
use App\Repository\NewsRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(EntityManagerInterface $manager, Request $request)
    {
     
       
         return $this->render('admin/index.html.twig', [
            
        ]); 
    }

     

       /**
     * @Route("/admin/news_del/{id}", name="news_del")
     */
    public function newsdel(EntityManagerInterface $manager, Request $request, NewsRepository $newsRepo,CommentRepository $comRepo, News $news )
    {
        $user = $this->getUser();
        //On regarde si l'utilisateur courant est l'auteur ou un administrateur
        if($user == $news->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles()) ){
            //Si oui on récupère tous les commentaires de l'article
            $comments = $news->getComments();
            foreach($comments as $c){
                //On Supprime tous les commentaires de l'article
                $comRepo->deleteOne($c->getId());
            }
            //On supprime l'article
            $newsRepo->deleteOne($news->getId());
            return $this->redirectToRoute('news');
        } else {
            return $this->redirectToRoute('app_login');
        }
       

    }
}
