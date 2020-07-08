<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UpdateRoleController extends AbstractController
{
    /**
     * @Route("/update/role/{id}", name="update_role")
     */
    public function editUser(Request $request,$id)
    {
        $repo = $this->getDoctrine()->getRepository(user::class);
        $user =$repo ->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $userRole = $user->getRoles();

        if ($userRole[0] == "ROLE_ADMIN") {
           
            $roles[] = 'ROLE_USER'; 

        }
        else 
        {
            $roles[] = 'ROLE_ADMIN'; 
        }
       
        $user->setRoles(array_unique($roles));
        $entityManager->persist($user);
       $entityManager->flush();

       return $this->redirectToRoute('showInfos', array(
        'id' => $user->getId()));

        return $this->render('update_role/index.html.twig', [
            'controller_name' => 'UpdateRoleController',
        ]);
    }
}
