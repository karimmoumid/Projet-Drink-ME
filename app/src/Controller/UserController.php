<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationForm;
use App\Repository\UserRepository;
use Cassandra\Type\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
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

    #[Route(path: '/parametres', name: 'app_user_params')]
    public function params(): Response
    {
        return $this->render('user/parametres.html.twig');
    }

    #[Route(path: '/liste_compte_pro', name: 'app_user_list')]
    public function list(UserRepository $userRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $userRepository->findAdminsAndEmployeesQuery();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('user/list.html.twig',compact('users'));
    }
    #[Route('/modifier_le_compte/{id}', name: 'app_user_modify')]
    public function modify(User $user,Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $form = $this->createForm(RegistrationForm::class, $user, ['is_edit' => true]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $getpassword = $form->get('plainPassword')->getData();
            if(!empty($getpassword)){
                $user->setPassword($userPasswordHasher->hashPassword($user, $getpassword));
            }
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('app_user_list' );
        }
        return $this->render('user/index.html.twig',compact('form'));
    }

    #[Route('/supprimer_le_compte/{id}', name: 'app_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request, EntityManagerInterface $em): Response
    {
        // Vérifier le token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete-user'.$user->getId(), $request->request->get('_token'))) {
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Le compte a bien été supprimé.');
        } else {
            $this->addFlash('error', 'Token CSRF invalide, suppression annulée.');
        }

        // Redirection après suppression (ex: vers la liste des utilisateurs)
        return $this->redirectToRoute('app_user_list');
    }



}
