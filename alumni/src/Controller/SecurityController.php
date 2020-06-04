<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\SignupType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;


class SecurityController extends AbstractController
{
    /**
     * @Route("/signup", name="signup")
     */
    public function signup(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        if ($this->getUser() != null) {
            return $this->redirectToRoute('profil');
        }
        //On Créer un nouveau User.
        $user = new User();
        //On créer le formulaire d'inscription prédéfini dans Form/SignupType
        $signupForm = $this->createForm(SignupType::class, $user);
        //on récupère les données entrées.
        $signupForm->handleRequest($request);
        //On vérifie le contenu du formulaire
        if ($signupForm->isSubmitted() && $signupForm->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(['ROLE_USER']);
            $user->setPhoto('avatar.png');
            $user->setIsConfirmed(false);
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/signup.html.twig', [
            'signupForm' => $signupForm->createView(),
        ]);
    }

    // pour la connexion 

    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->isGranted('ROLE_USER')){
            return $this->redirectToRoute('news');
        }
        // On récupère les erreurs liés à la connexion
        $error = $authenticationUtils->getLastAuthenticationError();

        //On récupère le dernier nom de user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('home/index.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
