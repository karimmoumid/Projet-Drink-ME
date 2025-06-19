<?php

namespace App\Controller;

use App\Form\RegistrationForm;
use App\Repository\UserRepository;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/moncompte', name: 'app_user')]
    public function index(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(RegistrationForm::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $getpassword = $form->get('plainPassword')->getData();
            if(!empty($getpassword)){
                $user->setPassword($userPasswordHasher->hashPassword($user, $getpassword));
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_main' );
        }
        return $this->render('user/index.html.twig',compact('form'));
    }
}
