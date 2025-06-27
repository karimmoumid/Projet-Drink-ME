<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    #[Route('/mes-commandes', name: 'app_my_orders')]
    public function myOrders(): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        // Ici vous récupéreriez les commandes depuis votre repository
        // Exemple : $orders = $this->orderRepository->findByUser($user);
        
        // Pour la démonstration, voici des données d'exemple :
        $orders = [
            [
                'id' => 12345,
                'date' => new \DateTime('2024-01-15'),
                'validation' => 'validated',
                'paye' => true,
                'delivery_time' => '3-5 jours',
                'delivry_max_date' => new \DateTime('2024-01-20'),
                'adress' => [
                    'postal_code' => '75001',
                    'city' => 'Paris',
                    'street' => 'Rue de Rivoli',
                    'apt' => 'Apt 12',
                    'number' => '123'
                ],
                'products' => [
                    [
                        'title' => 'Smartphone Samsung Galaxy S24',
                        'image' => 'https://images.pexels.com/photos/404280/pexels-photo-404280.jpeg?auto=compress&cs=tinysrgb&w=400',
                        'price' => 899.99
                    ],
                    [
                        'title' => 'Coque de protection',
                        'image' => 'https://images.pexels.com/photos/1295572/pexels-photo-1295572.jpeg?auto=compress&cs=tinysrgb&w=400',
                        'price' => 24.99
                    ]
                ]
            ],
            [
                'id' => 12344,
                'date' => new \DateTime('2024-01-10'),
                'validation' => 'pending',
                'paye' => false,
                'delivery_time' => '5-7 jours',
                'delivry_max_date' => new \DateTime('2024-01-17'),
                'adress' => [
                    'postal_code' => '75001',
                    'city' => 'Paris',
                    'street' => 'Rue de Rivoli',
                    'apt' => 'Apt 12',
                    'number' => '123'
                ],
                'products' => [
                    [
                        'title' => 'Ordinateur portable Dell XPS 13',
                        'image' => 'https://images.pexels.com/photos/205421/pexels-photo-205421.jpeg?auto=compress&cs=tinysrgb&w=400',
                        'price' => 1299.99
                    ]
                ]
            ]
        ];

        return $this->render('order/my_orders.html.twig', [
            'orders' => $orders,
        ]);
    }
}