<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\News;
use App\Entity\Post;
use App\Entity\PostAnswer;
use App\Repository\CommentRepository;
use App\Repository\PostAnswerRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(
        EntityManagerInterface $manager,
        Request $request,
        CommentRepository $repoCom,
        PostRepository $repoPost,
        PostAnswerRepository $repoPostAnswer
    ) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        //commentaires signalés
        $reportedComment = $repoCom->findBy(
            ['IsReported' => true],
            ['date' => 'DESC']
        );
        //réponse de post signalés
        $reportedPostAnswer = $repoPostAnswer->findBy(
            ['isReported' => true],
            ['Date' => 'DESC']
        );
        //post signalés
        $reportedPost = $repoPost->findBy(
            ['isReported' => true],
            ['Date' => 'DESC']
        );

        return $this->render('admin/index.html.twig', [
            'reportedComment' => $reportedComment,
            'reportedPost' => $reportedPost,
            'reportedPostAnswer' => $reportedPostAnswer,
        ]);
    }

    /**
     * @Route("/admin/comdel/{id}", name="admin_comment_del")
     */
    public function commentDel(
        EntityManagerInterface $manager,
        Request $request,
        CommentRepository $comRepo,
        Comment $comment
    ) {
        $user = $this->getUser();
        //On regarde qui est l'auteur du commentaire ou si l'utilisateur est administrateur
        if (
            $user == $comment->getAuthor() ||
            in_array('ROLE_ADMIN', $user->getRoles())
        ) {
            $news = $comment->getNews();
            //On supprime le commentaire
            $comRepo->deleteOne($comment->getId());
            //On redirige sur l'article.
            return $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/admin/postdel/{id}", name="admin_post_del")
     */
    //supprimer un post
    public function postDel(
        EntityManagerInterface $manager,
        Request $request,
        Post $Post
    ) {
        $user = $this->getUser();
        //On regarde qui est l'auteur du commentaire ou si l'utilisateur est administrateur
        if (
            $user == $Post->getAuthor() ||
            in_array('ROLE_ADMIN', $user->getRoles())
        ) {
            //On supprime le commentaire
            $manager->remove($Post);
            $manager->flush();
            //On redirige sur l'article.
            return $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/admin/postanswer/{id}", name="admin_postAnswer_del")
     */
    //supprimer la reponse à un post
    public function postAnswerDel(
        EntityManagerInterface $manager,
        Request $request,
        PostAnswer $PostAnswer
    ) {
        $user = $this->getUser();
        //On regarde qui est l'auteur du commentaire ou si l'utilisateur est administrateur
        if (
            $user == $PostAnswer->getAuthor() ||
            in_array('ROLE_ADMIN', $user->getRoles())
        ) {
            //On supprime le commentaire
            $manager->remove($PostAnswer);
            $manager->flush();
            //On redirige sur l'article.
            return $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/news/unreportcom/{id}", name="comment_unreport")
     */
    public function commentUnreport(
        EntityManagerInterface $manager,
        Request $request,
        Comment $comment
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $comment->setIsReported(0);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('news');
    }

    /**
     * @Route("/news/unreportPost/{id}", name="post_unreport")
     */
    public function postUnreport(
        EntityManagerInterface $manager,
        Request $request,
        Post $post
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $post->setIsReported(0);
            $manager->persist($post);
            $manager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('news');
    }

    /**
     * @Route("/news/unreportPostAnswer/{id}", name="postAnswer_unreport")
     */
    public function postAnswerUnreport(
        EntityManagerInterface $manager,
        Request $request,
        PostAnswer $postAnswer
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $postAnswer->setIsReported(0);
            $manager->persist($postAnswer);
            $manager->flush();

            return $this->redirectToRoute('admin');
        }

        return $this->redirectToRoute('news');
    }
}
