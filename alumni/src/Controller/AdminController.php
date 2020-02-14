<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\News;
use App\Form\NewType;
use App\Form\NewsEditType;
use App\Repository\CommentRepository;
use App\Repository\NewsRepository;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(EntityManagerInterface $manager, Request $request)
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
         return $this->render('admin/index.html.twig', [
            'newsForm' => $newsForm->createView(),
        ]); 
    }

      /**
     * @Route("/admin/news_edit/{id}", name="news_edit")
     */
    public function newsedit(EntityManagerInterface $manager, Request $request,News $news )
    {
        $user = $this->getUser();
        $media = $news->getMedia();
        if($user == $news->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles()) ){
        $newsForm = $this->createForm(NewsEditType::class, $news);
         //on récupère les données entrées.
         $newsForm->handleRequest($request);
         //On vérifie le contenu du formulaire
         if ($newsForm->isSubmitted() && $newsForm->isValid()) {
            if ($news->getMedia() != null  ){
            
                $file = $newsForm->get('media')->getData();      
                //On créer un nom unique pour l'image   
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                //On déplace le fichier vers "avatar_directory"
                $file->move($this->getParameter('newsImg_directory'),$filename);
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
