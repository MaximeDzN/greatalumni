<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\EditProfilType;
use App\Form\PasswordEditType;



class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function ProfilEdit(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $encoder)
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        // On créer un formulaire de modifcation
        $photo = $user->getPhoto();
        $editForm = $this->createForm(EditProfilType::class, $user);
        //on récupère les données entrées.
        $editForm->handleRequest($request);
        //On vérifie le contenu du formulaire de modification 
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            // Si l'utilisateur change la photo
            if ($user->getPhoto() != null) {
                //On récupère le fichier
                //On créer un nom unique pour l'image   
                $file = $editForm->get('photo')->getData();
                $filename = md5(uniqid()) . '.' . $file->guessExtension();
                //On déplace le fichier vers "avatar_directory"
                $file->move($this->getParameter('avatar_directory'), $filename);
                //On applique la nouvelle photo pour l'utilisateur
                $user->setPhoto($filename);
            } else {
                $user->setPhoto($photo);
            }
            $school_curriculum = $request->request->get('school_curriculum');
            $realSchool = [];
            for($i = 0; $i < sizeof($school_curriculum); $i++){
                if ($school_curriculum[$i] != ""){
                    array_push($realSchool,$school_curriculum[$i]);
                }
            }
            $user->setSchoolCurriculum($realSchool);
            $career = $request->request->get('career');
            $realCarreer = [];
            for($i = 0; $i < sizeof($career); $i++){
                if ($career[$i] != ""){
                    array_push($realCarreer,$career[$i]);
                }
            }
            $user->setCareer($realCarreer);
            $test = $request->request->get('test');
            $hobbies =  $request->request->get('hobbies');
            $realhobbies = [];
            for($i = 0; $i < sizeof($hobbies); $i++){
                if ($hobbies[$i] != ""){
                    array_push($realhobbies,$hobbies[$i]);
                }
            }
            $user->setHobbie($realhobbies);
            $manager->persist($user);
            $manager->flush();
            //return $this->redirectToRoute('profil');  
        }


        // On créer le formulaire de changement de mot de passe
        $pwdForm = $this->createForm(PasswordEditType::class);

        $pwdForm->handleRequest($request);

        if ($pwdForm->isSubmitted() && $pwdForm->isValid()) {
            //On regarde si le mot de passe pour autoriser la modification est le même que celui du user
            if ($encoder->isPasswordValid($user, $pwdForm['oldPassword']->getData())) {
                //Si Oui, on regarde si la nouvelle proposition de mot de passe correspond à l'ancien mot de passe
                if ($pwdForm['oldPassword']->getData() == $pwdForm['password']->getData()) {
                    //Si Oui, on autorise pas la modification
                    return $this->redirectToRoute('profil');
                } else {
                    //Si Non, on autorise la modification
                    $user->setPassword($encoder->encodePassword($user, $pwdForm['password']->getData()));
                    $manager->persist($user);
                    $manager->flush();
                    return $this->redirectToRoute('profil');
                }
            } else {
                //Si Non on autorise pas la modification
                return $this->redirectToRoute('profil');
            }
        }

        return $this->render('profil/index.html.twig', [
            'editForm' => $editForm->createView(),
            'pwdForm' => $pwdForm->CreateView(),
            'user' => $user
        ]);
    }
}
