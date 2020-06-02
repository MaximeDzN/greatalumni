<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


use App\Entity\User;
use App\Entity\Message;
use App\Form\PrivateMessageType;
use App\Repository\MessageRepository;

class PrivateMessageController extends AbstractController
{
    /**
     * @Route("/message", name="message")
     */

    public function indexMessage(MessageRepository $repoM)
     
     {
        if ($this->getUser() == null){
            return $this->redirectToRoute('app_login');  
        }


        $chatUserSender = $repoM->findChattedSender($this->getUser()->getId());
        $chatUserReceiver = $repoM->findChattedReceiver($this->getUser()->getId());

    return $this->render('message/index.html.twig', [
      'chatUserSender' => $chatUserSender,
      'chatUserReceiver' => $chatUserReceiver 
    ]);
     }

       /**
     * @Route("/message/write/{id}", name="writeMessage")
     */
    public function write(EntityManagerInterface $manager, Request $request,User $receiver, MessageRepository $repoM)
    {

        $chatMessage = $repoM->chatMessage($receiver->getId(), $this->getUser()->getId());


        $user = $this->getUser();
        //On Créer un nouveau User.
        $message = new Message();
        //On créer le formulaire 
        $messageForm = $this->createForm(PrivateMessageType::class, $message);
        //on récupère les données entrées.
        $messageForm->handleRequest($request);
        //On vérifie le contenu du formulaire
        if ($messageForm->isSubmitted() && $messageForm->isValid()) {

            $message->setSendDate(new \DateTime('now'));
            $message->setUserSend($user);
            $message->setUserReceive($receiver);
            $manager->persist($message);
            $manager->flush(); 
            return $this->redirect($request->getUri());
        }
        return $this->render('message/write.html.twig', [
            'messageForm' => $messageForm->createView(),
            'chatMessage' => $chatMessage
        ]);
    }

}