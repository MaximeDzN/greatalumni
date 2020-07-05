<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Entity\User;
use App\Form\PropertySearchType;
use PhpParser\Node\Name;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DirectoryController extends AbstractController
{
    /**
     * @Route("/directory", name="directory")
     */
    public function index(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);

        $users = $repo->findBy(['isConfirmed' => '1']);

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);

        $form->handleRequest($request);

        if (!(null == $request->query->get('property_search'))) {
            $name = $request->query->get('property_search')['Name'];
            $lastname = $request->query->get('property_search')['Lastname'];
            $promo = $request->query->get('property_search')['promo'];

            if ('' != $name && '' == $lastname && '' == $promo) {
                $users = $repo->findBy(['name' => $name]);
            } elseif ('' == $name && '' != $lastname && '' == $promo) {
                $users = $repo->findBy(['nickname' => $lastname]);
            } elseif ('' == $name && '' == $lastname && '' != $promo) {
                $users = $repo->findBy(['promo' => $promo]);
            } elseif ('' != $name && '' != $lastname && '' != $promo) {
                $users = $repo->findBy(['name' => $name, 'nickname' => $lastname, 'promo' => $promo]);
            } elseif (('' != $name && '' != $lastname && '' == $promo)) {
                $users = $repo->findBy(['name' => $name, 'nickname' => $lastname]);
            } elseif (('' != $name && '' == $lastname && '' != $promo)) {
                $users = $repo->findBy(['name' => $name, 'promo' => $promo]);
            } elseif (('' == $name && '' != $lastname && '' != $promo)) {
                $users = $repo->findBy(['nickname' => $lastname, 'promo' => $promo]);
            } else {
                $users = $repo->findBy(['isConfirmed' => '1']);
            }
        }

        return $this->render('directory/index.html.twig', [
            'controller_name' => 'DirectoryController',
            'users' => $users,
            'formSearch' => $form->createView(),
        ]);
    }

    /**
     * @Route("/directory/{id}", name="showInfos")
     *
     * @param mixed $id
     */
    public function showUserInfos($id)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->find($id);

        return $this->render('directory/showInfos.html.twig', [
            'user' => $user,
        ]);
    }
}
