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
use App\Form\RegistrationType;
use App\Form\ResetPassType;
use App\Repository\UserRepository;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;


class SecurityController extends AbstractController
{
    /**
     * @Route("/signup", name="signup")
     */
    public function signup(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder, \Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator)
    {
    //On Créer un nouveau User.
    $user = new User();
    // On initialise le formulaire
    $form = $this->createForm(RegistrationType::class);

    // On traite le formulaire
    $form->handleRequest($request);

    // Si le formulaire est valide
    if ($form->isSubmitted() && $form->isValid()) {

        // On récupère les données
        $donnees = $form->getData();           

        // On génère un token
        $token = $tokenGenerator->generateToken();

            
            $user->setRegistrationToken($token);
            $user->setEmail($donnees->getEmail());
            $user->setRoles(['ROLE_USER']);
            $user->setPhoto('avatar.png');
            $user->setIsConfirmed(false);
            $manager->persist($user);
            $manager->flush();
        
        // On génère l'URL de réinitialisation de mot de passe
        $url = $this->generateUrl('app_full_infos', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

        // On génère l'e-mail
        $message = (new \Swift_Message('Inscription à GreatAlumni'))
        ->setFrom('GeatAlumni@gmail.com')
        ->setTo($user->getEmail())
        ->setBody(
            "Bonjour,<br><br>Votre compte viens d'etre créé pour le site GreatAlumni. Veuillez cliquer sur le lien suivant pour complété vos informations: " . $url,
            'text/html'
        );

        // On envoie l'e-mail
       $mailer->send($message);

        // On crée le message flash de confirmation
        $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');
            

        // On redirige vers la page de admin
     //  return $this->redirectToRoute('admin');
    }

    // On envoie le formulaire à la vue
    return $this->render('security/registration.html.twig',['emailForm' => $form->createView()]);
    }

    
    /**
     * @Route("/full_infos/{token}", name="app_full_infos")
     */
    public function fullInfos(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
    {
        // On cherche un utilisateur avec le token donné
        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['registration_token' => $token]);

        $signupForm = $this->createForm(SignupType::class, $user);

        // Si l'utilisateur n'existe pas
        if ($user == null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('admin');
        }   
        $signupForm->handleRequest($request);
        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST') && $signupForm->isSubmitted() && $signupForm->isValid()) {
            //on récupère les données entrées.
            $hobbies =  $request->request->get('hobbies');
            $career = $request->request->get('career');
            $school_curriculum = $request->request->get('school_curriculum');
            
            // On supprime le token
            $user->setRegistrationToken(null);
            $hash = $passwordEncoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $user->setSchoolCurriculum(array_unique($school_curriculum));
            $user->setHobbie(array_unique($hobbies));
            $user->setCareer(array_unique($career));
            $user->setIsConfirmed(true);

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On crée le message flash
            $this->addFlash('message', 'Vos information ont mis à jour');

            // On redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
        
        }else {       
        
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/fullInfos.html.twig', [
                'signupForm' => $signupForm->createView(),
            ]);

        }

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
/**
 * @Route("/oubli-pass", name="app_forgotten_password")
 */
public function oubliPass(Request $request, UserRepository $users, \Swift_Mailer $mailer,TokenGeneratorInterface $tokenGenerator): Response
{
  
    // On initialise le formulaire
    $form = $this->createForm(ResetPassType::class);

    // On traite le formulaire
    $form->handleRequest($request);

    // Si le formulaire est valide
    if ($form->isSubmitted() && $form->isValid()) {
        // On récupère les données
        $donnees = $form->getData();

        // On cherche un utilisateur ayant cet e-mail
        $user = $users->findOneByEmail($donnees['email']);

        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On envoie une alerte disant que l'adresse e-mail est inconnue
            $this->addFlash('danger', 'Cette adresse e-mail est inconnue');
            
            // On retourne sur la page de admin
            return $this->redirectToRoute('admin');
        }

        // On génère un token
        $token = $tokenGenerator->generateToken();

        // On essaie d'écrire le token en base de données
        try{
            $user->setResetToken($token);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->addFlash('warning', $e->getMessage());
            return $this->redirectToRoute('admin');
        }

        // On génère l'URL de réinitialisation de mot de passe
        $url = $this->generateUrl('app_reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

        // On génère l'e-mail
        $message = (new \Swift_Message('Mot de passe oublié'))
        ->setFrom('GeatAlumni@gmail.com')
        ->setTo($user->getEmail())
        ->setBody(
            "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée
             pour le site GreatAlumni. Veuillez cliquer sur le lien suivant : " . $url,
            'text/html'
        )
    ;

        // On envoie l'e-mail
       $mailer->send($message);

        // On crée le message flash de confirmation
        $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');

        // On redirige vers la page de admin
     //  return $this->redirectToRoute('admin');
    }

    // On envoie le formulaire à la vue
    return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
}

/**
 * @Route("/reset_pass/{token}", name="app_reset_password")
 */
public function resetPassword(Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder)
{
    // On cherche un utilisateur avec le token donné
    $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(['reset_token' => $token]);
    // Si l'utilisateur n'existe pas
    if ($user == null) {
        // On affiche une erreur
        $this->addFlash('danger', 'Token Inconnu');
        return $this->redirectToRoute('admin');
    }   
    
    // Si le formulaire est envoyé en méthode post
    if ($request->isMethod('POST')) {
        
        // On supprime le token
        $user->setResetToken(null);
        
        // On chiffre le mot de passe
        $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('password')));

        // On stocke
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // On crée le message flash
        $this->addFlash('message', 'Mot de passe mis à jour');

        // On redirige vers la page de connexion
        return $this->redirectToRoute('app_login');
    }else {       
       
        // Si on n'a pas reçu les données, on affiche le formulaire
        return $this->render('security/reset_password.html.twig', ['token' => $token]);

    }

}

}
