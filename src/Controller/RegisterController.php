<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class RegisterController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function index(Request $request, 
                            UserPasswordHasherInterface $passwordHasher,
                            EntityManagerInterface $em): Response
    {
        $user = new User();
        $myForm = $this->createForm(RegistrationFormType::class);
        $myForm->handleRequest($request);
        if ($myForm->isSubmitted() && $myForm->isValid()) {
            // Jelszó hash-elése
            $hashedPassword = $passwordHasher->hashPassword(
                $user,
                $myForm->get('plainPassword')->getData()
            );
            $user->setPassword($hashedPassword);
            $user->setUsername($myForm->get('username')->getData());
            $user->setEmail($myForm->get('email')->getData());
    
            $em->persist($user);
            $em->flush();
            $this->addFlash(
               'success',
               'Your registration was successful! You can now log in.'
            );
    
            // automatikus login is lehet
            return $this->redirectToRoute('app_login');
        }

        return $this->render('register/register.html.twig', [
            'controller_name' => 'RegisterController',
            'registrationForm' => $myForm->createView(),
        ]);
    }
}
