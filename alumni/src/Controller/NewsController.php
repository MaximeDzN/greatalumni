<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\News;
use App\Form\NewType;
use App\Form\NewsEditType;


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
    public function index(NewsRepository $newsRepo)
    {
        $allNews = $newsRepo->findAllReverse();

        return $this->render('news/index.html.twig', [
            'allnews' => $allNews
        ]);
    }

    /**
     * @Route("/news/write", name="write")
     */
    public function write(EntityManagerInterface $manager, Request $request)
    {


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
            $filename = md5(uniqid()) . '.' . $file->guessExtension();
            //On déplace le fichier vers "avatar_directory"
            $file->move($this->getParameter('newsImg_directory'), $filename);
            //On applique la nouvelle photo pour l'utilisateur
            $news->setMedia($filename);
            $news->setAuthor($user);
            $manager->persist($news);
            $manager->flush();
            return $this->redirectToRoute('news');
        }
        return $this->render('news/write.html.twig', [
            'newsForm' => $newsForm->createView(),
        ]);
    }

    /**
     * @Route("/news/news_edit/{id}", name="news_edit")
     */
    public function newsedit(EntityManagerInterface $manager, Request $request, News $news)
    {
        $user = $this->getUser();
        $media = $news->getMedia();
        if ($user == $news->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles())) {
            $newsForm = $this->createForm(NewsEditType::class, $news);
            //on récupère les données entrées.
            $newsForm->handleRequest($request);
            //On vérifie le contenu du formulaire
            if ($newsForm->isSubmitted() && $newsForm->isValid()) {
                if ($news->getMedia() != null) {

                    $file = $newsForm->get('media')->getData();
                    //On créer un nom unique pour l'image   
                    $filename = md5(uniqid()) . '.' . $file->guessExtension();
                    //On déplace le fichier vers "avatar_directory"
                    $file->move($this->getParameter('newsImg_directory'), $filename);
                    //On applique la nouvelle photo pour l'utilisateur
                    $news->setMedia($filename);
                } else {
                    $news->setMedia($media);
                }
                $manager->persist($news);
                $manager->flush();
                return $this->redirectToRoute('news');
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
        return $this->render('news/edit.html.twig', [
            'news' => $news,
            'newsForm' => $newsForm->createView(),
        ]);
    }


    /**
     * @Route("/news/{id}", name="news_details")
     */

    public function newsDetails(EntityManagerInterface $manager, Request $request, CommentRepository $comRepo, ScoreRepository $scoRepo, News $news)
    {
        $user = $this->getUser();
        $comments = $news->getComments();
        $notes = $news->getScores();
        $voted = false;
        $note = null;
        if (count($notes) > 0) {
            $note = round($scoRepo->moyenneNote($news->getId()), 2);
        }
        foreach ($notes as $n) {
            if ($n->getUser() == $user) {
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


        if ($voted == true) {
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
    public function commentDel(EntityManagerInterface $manager, Request $request, CommentRepository $comRepo, Comment $comment)
    {
        $user = $this->getUser();
        //On regarde qui est l'auteur du commentaire ou si l'utilisateur est administrateur
        if ($user == $comment->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles())) {
            $news = $comment->getNews();
            //On supprime le commentaire
            $comRepo->deleteOne($comment->getId());
            //On redirige sur l'article.
            return $this->redirectToRoute('news_details', ['id' => $news->getId(), 'news' => $news]);
        } else {
            return $this->redirectToRoute('app_login');
        }
    }
}
