<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UpdateRoleController extends AbstractController
{
    /**
     * @Route("/update/role/{id}", name="update_role")
     *
     * @param mixed $id
     */
    public function editUser(Request $request, $id, EntityManagerInterface $entityManager)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->find($id);
        $userRole = $user->getRoles();

        if ('ROLE_ADMIN' == $userRole[0]) {
            $roles[] = 'ROLE_USER';
        } else {
            $roles[] = 'ROLE_ADMIN';
        }

        $user->setRoles(array_unique($roles));
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('showInfos', [
            'id' => $user->getId(), ]);

        return $this->render('update_role/index.html.twig', [
            'controller_name' => 'UpdateRoleController',
        ]);
    }
}
