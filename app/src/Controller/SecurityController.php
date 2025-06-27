<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\PasswordChangeForm;
use App\Form\PasswordRequestChangeForm;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/mot_de_passe_oublier', name: 'app_password_request')]
    public function passwordrequest(AuthenticationUtils $authenticationUtils, Request $request, EntityManagerInterface $em, EmailService $emailService): Response
    {
        $form = $this->createForm(PasswordRequestChangeForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $email = $form->get('email')->getData();
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                $token = bin2hex(random_bytes(32));
                $user->setToken($token);
                $user->setPasswordRequestedAt((new \DateTimeImmutable())->modify('+1 hour'));
                $em->persist($user);
                $em->flush();
$link = $this->generateUrl('app_password_reset', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
                $emailService->sender(
                    'noreply@drinkme.com',
                    $user->getEmail(),
                    'Demande de réinisialisation de mot de passe',
                    'passwordRequest',
                    [
                        'user' => $user,
                        'link' => $link
                    ]

                );
            }
            $this->addFlash('success', 'un email à été envoyer pour réinstaller le mot de passe');
            return $this->redirectToRoute('app_login');

        }
        return $this->render('security/passwordRequest.html.twig', compact('form'));

    }

    #[Route(path: '/changer_mot_de_passe/{token}', name: 'app_password_reset')]
    public function passwordreset(string $token,AuthenticationUtils $authenticationUtils, Request $request, EntityManagerInterface $em, EmailService $emailService, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['token' => $token]);
        $now = new \DateTimeImmutable();
        if (!$user || $user->getPasswordRequestedAt() < $now) {
            $this->addFlash('danger', 'Token invalide ou expérée');
            return $this->redirectToRoute('app_login');
        }
        $form = $this->createForm(PasswordChangeForm::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
$password = $form->get('password')->getData();
$passwordHash = $passwordHasher->hashPassword($user, $password);
$user->setPassword($passwordHash);
$user->setToken(null);
$user->setPasswordRequestedAt(null);

$em->persist($user);
$em->flush();
            $this->addFlash('success', 'Votre mot de passe a été réinitialisé avec succès.');
            return $this->redirectToRoute('app_login');
        };
        return $this->render('security/passwordReset.html.twig', compact('form'));

    }






}

