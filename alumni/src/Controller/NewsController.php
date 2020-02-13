<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\News;
use App\Entity\Comment;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\NewType;
use App\Form\CommentType;

use App\Repository\CommentRepository;
use App\Repository\NewsRepository;



class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news")
     */
    public function index(EntityManagerInterface $manager, Request $request,NewsRepository $newsRepo )
    {
        $allNews = $newsRepo->findAll();


        $user = $this->getUser();
         //On Créer un nouveau User.
         $news = new News();
         //On créer le formulaire d'inscription prédéfini dans Form/NewType
         $newsForm = $this->createForm(NewType::class, $news);
         //on récupère les données entrées.
         $newsForm->handleRequest($request);
         //On vérifie le contenu du formulaire
         if ($newsForm->isSubmitted() && $newsForm->isValid()) {
             
            $news->setDate(new \DateTime('now'));
                $file = $newsForm->get('media')->getData();      
                //On créer un nom unique pour l'image   
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                //On déplace le fichier vers "avatar_directory"
                $file->move($this->getParameter('newsImg_directory'),$filename);
                //On applique la nouvelle photo pour l'utilisateur
                $news->setMedia($filename);
                $news->setAuthor($user);
             $manager->persist($news);
             $manager->flush();
             return $this->redirectToRoute('news');
         }
 
        return $this->render('news/index.html.twig', [
            'newsForm' => $newsForm->createView(),
            'allnews' => $allNews
        ]);
    }

     /**
     * @Route("/news/{id}", name="news_details")
     */

    public function newsDetails(EntityManagerInterface $manager, Request $request,CommentRepository $comRepo, News $news )
    {
        $user = $this->getUser();
        $comments = $comRepo->findBy(['News'=> $news->getId()]);

        $comment = new Comment();
        //On créer le formulaire d'inscription prédéfini dans Form/NewType
        $commentForm = $this->createForm(CommentType::class, $comment);
        //on récupère les données entrées.
        $commentForm->handleRequest($request);
        //On vérifie le contenu du formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            
            $comment->setDate(new \DateTime('now'));
            $comment->setAuthor($user);
            $comment->setNews($news);
        
            $manager->persist($comment);
            $manager->flush();
            return $this->redirect($request->getUri());
        }



        return $this->render('news/details.html.twig', [
            'news' => $news,
            'commentForm' => $commentForm->createView(),
            'comments' => $comments
        ]); 
    }



      /**
     * @Route("/news/newsdel/{id}", name="news_del")
     */
    public function newsdel(EntityManagerInterface $manager, Request $request, NewsRepository $newsRepo,CommentRepository $comRepo, News $news )
    {
        $user = $this->getUser();
        if($user == $news->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles()) ){
            $comments = $news->getComments();
            foreach($comments as $c){
                $comRepo->deleteOne($c->getId());
            }
            $newsRepo->deleteOne($news->getId());
            return $this->redirectToRoute('news');
        } else {
            return $this->redirectToRoute('app_login');
        }
       

    }

      /**
     * @Route("/news/comdel/{id}", name="comment_del")
     */
    public function commentDel(EntityManagerInterface $manager, Request $request,CommentRepository $comRepo, Comment $comment )
    {
        $user = $this->getUser();
        if($user == $comment->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles()) ){
            $news = $comment->getNews();
            $comRepo->deleteOne($comment->getId());
            return $this->redirectToRoute('news_details',['id' => $news->getId(), 'news' => $news]);
        } else {
            return $this->redirectToRoute('app_login');
        }
       

    }


}
