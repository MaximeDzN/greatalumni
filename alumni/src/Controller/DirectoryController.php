<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Entity\User;
use App\Form\PropertySearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use PhpParser\Node\Name;

class DirectoryController extends AbstractController
{
    /**
     * @Route("/directory", name="directory")
     */
    public function index(Request $request)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);

        $users = $repo->findAll();

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

       if(!($request->query->get('property_search') == null))
       {
          
           $name = $request->query->get('property_search')['Name'];
           $lastname = $request->query->get('property_search')['Lastname'];
           $promo = $request->query->get('property_search')['promo'];
           
            if ($name!= "" && $lastname == "" && $promo =="") 
            {
                $users = $repo->findBy(['name'=>$name]);
            }
            else if ($name== "" && $lastname != "" && $promo =="") 
            {
                $users = $repo->findBy(['nickname'=>$lastname]);
            }
            else if ($name== "" && $lastname == "" && $promo !="") 
            {
                $users = $repo->findBy(['promo'=>$promo]);
            }
            else if  ($name!= "" && $lastname != "" && $promo !="") 
            {
                $users = $repo->findBy(['name'=>$name,'nickname'=>$lastname,'promo'=>$promo]);
            }
            else  if (($name!= "" && $lastname != "" && $promo =="")) 
            {
                $users = $repo->findBy(['name'=>$name,'nickname'=>$lastname]);
            }
            else if (($name!= "" && $lastname == "" && $promo !="")) 
            {
                $users = $repo->findBy(['name'=>$name,'promo'=>$promo]);            
            }
            else if (($name== "" && $lastname != "" && $promo !=""))
            {
                $users = $repo->findBy(['nickname'=>$lastname,'promo'=>$promo]);
            }
            else 
            {
                $users = $repo->findAll();
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
     */
    public function showUserInfos($id)
    {
        $repo = $this->getDoctrine()->getRepository(user::class);
        $user =$repo ->find($id);
        return $this->render('directory/showInfos.html.twig', [            
            'user' => $user
        ]);
    }

    
}
