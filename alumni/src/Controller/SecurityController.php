<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Form\SignupType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/signup", name="signup")
     */
    public function signup(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {
        //On Créer un nouveau User.
        $user = new User();
        //On créer le formulaire d'inscription prédéfini dans Form/SignupType
        $signupForm = $this->createForm(SignupType::class,$user);
        //on récupère les données entrées.
        $signupForm->handleRequest($request);
        //On vérifie le contenu du formulaire
        if ($signupForm->isSubmitted() && $signupForm->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setRoles(['ROLE_USER']);
            $user->setPhoto('images/avatar.png');
            $manager->persist($user);
            $manager->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('security/signup.html.twig', [
            'signupForm' => $signupForm->createView(),
        ]);
    }
}
