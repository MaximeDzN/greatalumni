<?php

namespace App\Controller;

use App\Entity\Report;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use  App\Form\ReportType;
use DateTime;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;



class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index(Request $request,EntityManagerInterface $manager)
    {
        $report = new Report();
        $form = $this->createForm(ReportType::class);
        $form->handleRequest($request);

        if ( $form->isSubmitted() && $form->isValid()) {
            $donnees = $form->getData(); 
            dump($donnees);
           
            $date = new DateTime(); 

            $report->setLocalErreur($donnees->getLocalErreur());
            $report->setAlertLevel($donnees->getAlertLevel());
            $report->setComments($donnees->getComments());
            $report->setDate($date);

            $manager->persist($report);
            $manager->flush();
        }


        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
            'form'=> $form->CreateView(),
        ]);
    }
}
