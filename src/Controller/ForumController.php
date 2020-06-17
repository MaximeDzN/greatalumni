<?php

namespace App\Controller;


use App\Entity\PostType;
use App\Entity\Post;
use App\Entity\PostAnswer;
Use App\Entity\User;

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

    public function __construct(PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/forum", name="forum")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(PostType::class);
        $postTypes = $repo->findAll();
        return $this->render('forum/index.html.twig', [
            'postTypes' => $postTypes
        ]);
    }

    /**
     * @Route("/forum/newposttype", name="newposttype")
     */
    public function newposttype(Request $request)
    {
        $postType = new PostType();
        $formpostType = $this->createForm(PosttypeType::class, $postType);
        $formpostType->handleRequest($request);
        if($formpostType->isSubmitted() && $formpostType->isValid())
        {
            $this->entityManager->persist($postType);
            $this->entityManager->flush();
            return $this->redirectToRoute('forum', [
                'id' => $postType->getId(),
                'slug' => $postType->getSlug()
            ]);
        }
        return $this->render('forum/newposttype.html.twig', [
            'postType' => $postType,
            'formpostType' => $formpostType->createView()
        ]);
    }


    /**
     * @Route("/{slug}-{id}", name="show",methods={"GET"}, requirements={"slug"="^[a-zA-Z0-9-_]+$"} )
     */
    public function show($id,PaginatorInterface $paginator,Request $request,postType $postType)
    {
        $posts = $paginator->paginate(
            $postType->getPosts(),
            $request->query->getInt('page', 1),
            12
        );
            return $this->render('forum/show.html.twig', [
            'postType' => $postType,
            'posts' => $posts
        ]);
    }

    /**
     * @Route("forum/{id}", name="supprimerCat", methods={"DELETE"}) 
     */
    public function supprimerCat(Request $request, PostType $postType)
    {
        if($this->isCsrfTokenValid('delete' . $postType->getId(), $request->get('_token'))) {
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
        $user = $this->getUser();
        $photo = $user->getPhoto();
        $post = new Post();
       

        $form = $this->createForm(NewPostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setDate(new \DateTime('now'));
           
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
     * @Route("/forum/post/{slug}.{id}", name="forum.showPost", methods={"GET", "POST"})
     */
    public function showPost(Post $post,  Request $request)
    {
        $user = $this->getUser();
        $photo = $user->getPhoto();
        $postAnswer = new PostAnswer();
        
        $postAnswer->setPost($post);
        $form = $this->createForm(PostAnswerType::class, $postAnswer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postAnswer->setDate(new \DateTime('now'));
            $postAnswer->setAuthor($user);
            $user->setPhoto($photo);
           
            $this->entityManager->persist($postAnswer);
            $this->entityManager->flush();
            return $this->redirectToRoute('forum.showPost', ['slug' => $post->getSlug(), 'id' => $post->getId()]);
        }

        return $this->render('forum/post/showPost.html.twig', ['post' => $post, 'form' => $form->createView()]);
    }

    /**
     * @Route("/forum/post/'slug}-{id}", name="editPost", methods="GET|POST")
     */
    public function editPost(Post $post, Request $request)
    {
        $form = $this->createForm(NewPostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->entityManager->flush();
        
            return $this->redirectToRoute('forum.showPost', ['slug' => $post->getSlug(), 'id' => $post->getId()]);
        }

        return $this->render('forum/post/editPost.html.twig', [
            'post' => $post,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("forum/post/{id}", name="supprimerpost", methods={"DELETE"}) 
     */
    public function supprimer(Request $request, Post $post)
    {
        if($this->isCsrfTokenValid('delete' . $post->getId(), $request->get('_token'))) {
            $this->entityManager->remove($post);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('forum');
    }

}
