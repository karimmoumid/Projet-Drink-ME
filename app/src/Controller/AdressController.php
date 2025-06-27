<?php

namespace App\Controller;

use App\Entity\Adress;
use App\Form\AdressForm;
use App\Repository\AdressRepository;
use App\Service\AddressManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/adress')]
final class AdressController extends AbstractController
{
    #[Route(name: 'app_adress_index', methods: ['GET'])]
    #[isGranted('ROLE_CUSTOMER')]
    public function index(AdressRepository $adressRepository): Response
    {
        return $this->render('adress/index.html.twig', [
            'adresses' => $adressRepository->findBy(['users' => $this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_adress_new', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_CUSTOMER')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressForm::class, $adress);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $adress->setUsers($this->getUser());
            $entityManager->persist($adress);
            $entityManager->flush();

            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adress/new.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adress_show', methods: ['GET'])]
    #[isGranted('ROLE_CUSTOMER')]
    public function show(Adress $adress): Response
    {
        $user = $this->getUser();
        if ($adress->getUsers() !== $user) {
            $this->addFlash('danger', 'Vous n\'avez pas le droit' );
            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }
        return $this->render('adress/show.html.twig', [
            'adress' => $adress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_adress_edit', methods: ['GET', 'POST'])]
    #[isGranted('ROLE_CUSTOMER')]
    public function edit(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($adress->getUsers() !== $user) {
            $this->addFlash('danger', 'Vous n\'avez pas le droit' );
            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }
        $form = $this->createForm(AdressForm::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('adress/edit.html.twig', [
            'adress' => $adress,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_adress_delete', methods: ['POST'])]
    #[isGranted('ROLE_CUSTOMER')]
    public function delete(Request $request, Adress $adress, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        if ($adress->getUsers() !== $user) {
            $this->addFlash('danger', 'Vous n\'avez pas le droit' );
            return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
        }
        if ($this->isCsrfTokenValid('delete'.$adress->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($adress);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_adress_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/favorite', name: 'address_set_favorite', methods: ['POST'])]
    #[isGranted('ROLE_CUSTOMER')]
    public function setFavorite(
        Adress $address,
        AddressManager $addressManager,
        Request $request
    ): JsonResponse {
        // Optionnel : vérifier que l'utilisateur courant est bien propriétaire de l'adresse
        $user = $this->getUser();

        if ($address->getUsers() !== $user) {
            return new JsonResponse(['error' => 'Accès refusé.'], 403);
        }

        // Appelle le service pour gérer la logique
        $addressManager->setFavoriteAddress($address);

        return new JsonResponse(['success' => true]);
    }
}
