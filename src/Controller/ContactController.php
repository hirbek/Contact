<?php

namespace App\Controller;

use App\Entity\Message;
use App\Form\ContactFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    #[Route('/contact', name: 'app_contact')]
    public function contact(Request $request): Response
    {

        $message = new Message();
        $form = $this->createForm(ContactFormType::class, $message);

        $form ->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $newMessage = $form->getData();
                $this->em->persist($newMessage);
                $this->em->flush();
                $this->addFlash(
                'notice',
                'Köszönjük szépen a kérdésedet. Válaszunkkal hamarosan keresünk a megadott e-mail címen.'
            );
                return $this->redirectToRoute('app_contact');
            }

            return $this->render('contact/index.html.twig',[
            'form' => $form->createView()
            ]);
    
    }
}
