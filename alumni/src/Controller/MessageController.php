<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

use BackendBundle\Entity\User;
use BackendBundle\Entity\Message;


class MessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */

    public function EditeMessage()
     
     {
        if ($this->getUser() == null){
            return $this->redirectToRoute('app_login');  
        }

    return $this->render('message/index.html.twig', [
       
    ]);
     }
}