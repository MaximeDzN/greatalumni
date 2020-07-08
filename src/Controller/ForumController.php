<?php

namespace App\Controller;

use App\Entity\PostType;
use App\Entity\Post;
use App\Entity\PostAnswer;
use App\Entity\User;

use App\Form\NewPostType;
use App\Form\PostAnswerType;
use App\Form\PosttypeType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\PostRepository;
use App\Repository\PostTypeRepository;
use App\Repository\PostAnswerRepository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Request;

use Knp\Component\Pager\PaginatorInterface;

class ForumController extends AbstractController
{
    private $entityManager;
    private $postRepository;

    public function __construct(
        PostRepository $postRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/forum", name="forum")
     */
    public function index()
    {
        //Vérification que la personne est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $repo = $this->getDoctrine()->getRepository(PostType::class);
        $postTypes = $repo->findAll();
        return $this->render('forum/index.html.twig', [
            'postTypes' => $postTypes,
        ]);
    }

    /**
     * @Route("/forum/newpost", name="newposttype")
     */
    public function newposttype(Request $request)
    {
        //Vérification que ce soit bien un admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $postType = new PostType();
        $formpostType = $this->createForm(PosttypeType::class, $postType);
        $formpostType->handleRequest($request);
        if ($formpostType->isSubmitted() && $formpostType->isValid()) {
            $this->entityManager->persist($postType);
            $this->entityManager->flush();
            return $this->redirectToRoute('forum', [
                'id' => $postType->getId(),
                'slug' => $postType->getSlug(),
            ]);
        }
        return $this->render('forum/newposttype.html.twig', [
            'postType' => $postType,
            'formpostType' => $formpostType->createView(),
        ]);
    }

    /**
     * @Route("/forum/{id}", name="show",methods={"GET"}, requirements={"slug"="^[a-zA-Z0-9-_]+$"} )
     */

    public function show($id, PaginatorInterface $paginator, Request $request, PostType $PostType, PostRepository $repoPost)
    {
        //Vérification que la personne est connecté

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $posts = $paginator->paginate(
            $PostType->getPosts(),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('forum/show.html.twig', [
            'postType' => $PostType,
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("forum/{id}", name="supprimerCat", methods={"DELETE"})
     */
    public function supprimerCat(Request $request, PostType $postType)
    {
        //Vérification que ce soit bien un admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $postType->getId(), $request->get('_token'))) {

            $this->entityManager->remove($postType);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('forum');
    }

    /*************************  partie post **********************************/

    /**
     * @Route("/forum/post/newpost", name="newpost")
     */
    public function newpost(Request $request)
    {
        //Vérification que la personne est connecté

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $photo = $user->getPhoto();
        $post = new Post();

        $form = $this->createForm(NewPostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setDate(new \DateTime('now'));
            $post->setIsReported(0);
            $post->setAuthor($user);
            $user->setPhoto($photo);

            $this->entityManager->persist($post);
            $this->entityManager->flush();
            return $this->redirectToRoute('forum.showPost', ['slug' => $post->getSlug(), 'id' => $post->getId()]);
        }
        return $this->render('forum/post/newpost.html.twig', [
            'post' => $post,
            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/forum/post/details/{id}", name="forum.showPost", methods={"GET", "POST"})
     */
    public function showPost(Post $post, Request $request)
    {
        //Vérification que la personne est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();

        $postAnswer = new PostAnswer();

        $postAnswer->setPost($post);

        $form = $this->createForm(PostAnswerType::class, $postAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $postAnswer->setDate(new \DateTime('now'));
            $postAnswer->setIsReported(0);
            $postAnswer->setAuthor($user);
            
            $this->entityManager->persist($postAnswer);
            $this->entityManager->flush();
            return $this->redirectToRoute('forum.showPost', [
                'slug' => $post->getSlug(),
                'id' => $post->getId(),
            ]);
        }

        return $this->render('forum/post/showPost.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/forum/post/edit/{id}", name="editPost", methods="GET|POST")
     */
    public function editPost(Post $post, Request $request,$id)
    {
        //Vérification que la personne est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $form = $this->createForm(NewPostType::class, $post);
        $form->handleRequest($request);
        $user = $this->getUser();
        if ($user == $post->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles())) {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->entityManager->flush();

                return $this->redirectToRoute('forum.showPost', ['slug' => $post->getSlug(), 'id' => $post->getId()]);

            }
        } else {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('forum/post/editPost.html.twig', [
            'post' => $post,
            'form' => $form->createView(),
        ]);
    }

  
    /**
     * @Route("forum/post/{id}", name="supprimerpost", methods={"DELETE"})
     */
    public function supprimer(Request $request, Post $post)
    {
        //Vérification que la personne est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        //recuperation de l'utilisateur
        $user = $this->getUser();
        if ($user == $post->getAuthor() || in_array('ROLE_ADMIN', $user->getRoles())) {

            if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->get('_token'))) {

                $this->entityManager->remove($post);
                $this->entityManager->flush();
                return $this->redirectToRoute('forum');
            }
        } else {
            return $this->redirectToRoute('app_login');

        }
    }

       /**
     * @Route("forum/postAnswer/{id}", name="supprimerpostanswer", methods={"DELETE"})
     */
    public function supprimerAnswer(Request $request, PostAnswer $postAnswer)
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        $post = $postAnswer->getPost();
        if (
            $user == $postAnswer->getAuthor() ||
            in_array('ROLE_ADMIN', $user->getRoles())
        ) {
            if (
                $this->isCsrfTokenValid(
                    'delete' . $postAnswer->getId(),
                    $request->get('_token')
                )
            ) {
                $this->entityManager->remove($postAnswer);
                $this->entityManager->flush();
                return $this->redirectToRoute('forum.showPost', [
                    'slug' => $post->getSlug(),
                    'id' => $post->getId(),
                ]);
            }
        } else {
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * @Route("/forum/post/reportPost/{id}", name="post_report")
     */

    public function postReport(
        EntityManagerInterface $manager,
        Request $request,
        Post $post
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $post->setIsReported(1);
        $manager->persist($post);
        $manager->flush();
        return $this->redirectToRoute('forum.showPost', [
            'slug' => $post->getSlug(),
            'id' => $post->getId(),
        ]);
    }

    /**
     * @Route("/forum/post/reportPostAnswer/{id}", name="postAnswer_report")
     */

    public function postAnswerReport(
        EntityManagerInterface $manager,
        Request $request,
        PostAnswer $postAnswer
    ) {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $postAnswer->setIsReported(1);
        $manager->persist($postAnswer);
        $manager->flush();
        $post = $postAnswer->getPost();
        return $this->redirectToRoute('forum.showPost', [
            'slug' => $post->getSlug(),
            'id' => $post->getId(),
        ]);
    }

}
