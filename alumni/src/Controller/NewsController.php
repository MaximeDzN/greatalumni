<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\News;

use App\Entity\Comment;
use App\Entity\Score;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\CommentType;
use App\Form\ScoreType;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;
use App\Repository\ScoreRepository;




class NewsController extends AbstractController
{
    /**
     * @Route("/news", name="news")
     */
    public function index(NewsRepository $newsRepo )
    {
        $allNews = $newsRepo->findAllReverse();
 
        return $this->render('news/index.html.twig', [
            'allnews' => $allNews
        ]);
    }

     /**
     * @Route("/news/{id}", name="news_details")
     */

    public function newsDetails(EntityManagerInterface $manager, Request $request,CommentRepository $comRepo,ScoreRepository $scoRepo, News $news )
    {
        $user = $this->getUser();
        $comments = $news->getComments();
        $notes = $news->getScores();
        $voted = false;
        $note = null;
        if (count($notes) > 0){
            $note = round($scoRepo->moyenneNote($news->getId()),2);
        }
        foreach($notes as $n){
            if ($n->getUser() == $user ){
                $voted = true;
            }
        }

        $score = new Score();
        $scoreForm = $this->createForm(ScoreType::class, $score);
        $scoreForm->handleRequest($request);
        if ($scoreForm->isSubmitted() && $scoreForm->isValid()) {
            $score->setUser($user);
            $score->setNews($news);
            $manager->persist($score);
            $manager->flush();
            return $this->redirect($request->getUri());
        }

        
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


        if($voted == true){
            return $this->render('news/details.html.twig', [
                'news' => $news,
                'commentForm' => $commentForm->createView(),
                'comments' => $comments,
                'note' => $note,
            ]); 

        } else {
            return $this->render('news/details.html.twig', [
                'news' => $news,
                'commentForm' => $commentForm->createView(),
                'comments' => $comments,
                'note' => $note,
                'scoreForm' => $scoreForm->createView()
            ]); 
        }
    }

      /**
     * @Route("/news/comdel/{id}", name="comment_del")
     */
    public function commentDel(EntityManagerInterface $manager, Request $request,CommentRepository $comRepo, Comment $comment )
    {
        $user = $this->getUser();
        //On regarde qui est l'auteur du commentaire ou si l'utilisateur est administrateur
        if($user == $comment->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles()) ){
            $news = $comment->getNews();
            //On supprime le commentaire
            $comRepo->deleteOne($comment->getId());
            //On redirige sur l'article.
            return $this->redirectToRoute('news_details',['id' => $news->getId(), 'news' => $news]);
        } else {
            return $this->redirectToRoute('app_login');
        }
       
    }


}
