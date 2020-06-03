<?php

namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;


use App\Entity\User;
use App\Entity\Message;
use App\Entity\ChatMessage;
use App\Entity\Chat;
use App\Form\PrivateMessageType;
use App\Repository\MessageRepository;
use App\Repository\ChatRepository;

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

    return $this->render('message/index.html.twig', [
      'chatUserSender' => $chatUserSender,
      'chatUserReceiver' => $chatUserReceiver 
    ]);
     }

       /**
     * @Route("/message/write/{id}", name="writeMessage")
     */
    public function write(EntityManagerInterface $manager, Request $request,User $receiver, MessageRepository $repoM, ChatRepository $repoC)
    {

         if ($repoC->findOneBy(['participantA' => $this->getUser()->getId(),"participantB" => $receiver->getId()])){

            $chat = $repoC->findOneBy(['participantA' => $this->getUser()->getId(),"participantB" => $receiver->getId()]);
           
            $isexist = true;
         }else if($repoC->findOneBy(['participantB' => $this->getUser()->getId(),"participantA" => $receiver->getId()])) {
            $chat = $repoC->findOneBy(['participantB' => $this->getUser()->getId(),"participantA" => $receiver->getId()]);
         
            $isexist = true;
         }  else {
             $isexist = false;
         }
         
         

        if ($isexist){
            $test = $chat;
            $user = $this->getUser();
            $message = new Message();
            $chatMessage = new ChatMessage();
            //On créer le formulaire 
            $messageForm = $this->createForm(PrivateMessageType::class, $message);
            //on récupère les données entrées.
            $messageForm->handleRequest($request);
            //On vérifie le contenu du formulaire
            if ($messageForm->isSubmitted() && $messageForm->isValid()) {
                $message->setSendDate(new \DateTime('now'));
                $message->setUserSend($user);
                $manager->persist($message);
                $chatMessage->setChat($chat);
                $chatMessage->addMessage($message);
                $manager->persist($chatMessage);
                $manager->flush(); 
                return $this->redirect($request->getUri());
            }

            return $this->render('message/write.html.twig', [
                'messageForm' => $messageForm->createView(),
                'test' => $test
            ]);
        }else {

            $user = $this->getUser();
            //On Créer un nouveau User.
            $message = new Message();
            $chat = new Chat();
            $chatMessage = new ChatMessage();
            //On créer le formulaire 
            $messageForm = $this->createForm(PrivateMessageType::class, $message);
            //on récupère les données entrées.
            $messageForm->handleRequest($request);
            //On vérifie le contenu du formulaire
            if ($messageForm->isSubmitted() && $messageForm->isValid()) {
                $chat->setParticipantA($this->getUser());
                $chat->setParticipantB($receiver);
                $manager->persist($chat);
                $message->setSendDate(new \DateTime('now'));
                $message->setUserSend($user);
                $manager->persist($message);
                $chatMessage->setChat($chat);
                $chatMessage->addMessage($message);
                $manager->persist($chatMessage);
                $manager->flush(); 
                return $this->redirect($request->getUri());
            }

            return $this->render('message/write.html.twig', [
                'messageForm' => $messageForm->createView(),
                'test' => 'non'
            ]);

        }
      
    }

}