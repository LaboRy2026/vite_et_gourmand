<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class MenuController extends AbstractController
{
    private array $menus = [
        1 => [
            'titre' => 'Menu de Noël Tradition',
            'description' => 'Un repas festif pour partager un moment gourmand.',
            'theme' => 'Noël',
            'regime' => 'Classique',
            'prix' => 180,
            'personnes' => 6,
            'entree' => 'Saumon fumé sur blinis',
            'plat' => 'Volaille rôtie et pommes de terre',
            'dessert' => 'Bûche au chocolat',
            'allergenes' => 'Poisson, blé (gluten), lait, œufs',
            'conditions' => 'Commander au moins 72 heures à l’avance.'
        ],
        2 => [
            'titre' => 'Menu Mariage Élégance',
            'description' => 'Une sélection raffinée pour votre réception.',
            'theme' => 'Mariage',
            'regime' => 'Classique',
            'prix' => 600,
            'personnes' => 20,
            'entree' => 'Salade de crevettes',
            'plat' => 'Filet de volaille et légumes',
            'dessert' => 'Entremets aux fruits',
            'allergenes' => 'À confirmer selon les recettes',
            'conditions' => 'Réservation à anticiper.'
        ],
        3 => [
            'titre' => 'Menu Jardin Végétarien',
            'description' => 'Des recettes végétariennes gourmandes.',
            'theme' => 'Toutes occasions',
            'regime' => 'Végétarien',
            'prix' => 150,
            'personnes' => 6,
            'entree' => 'Salade de légumes de saison',
            'plat' => 'Risotto aux champignons',
            'dessert' => 'Tarte aux fruits',
            'allergenes' => 'À confirmer selon les recettes',
            'conditions' => 'Disponibilité à confirmer.'
        ]
    ];

    #[Route('/menus', name: 'app_menus')]
    public function index(): Response
    {
        return $this->render('menus.html.twig');
    }

    #[Route('/menus/{id}', name: 'app_menu_detail', requirements: ['id' => '\d+'])]
    public function detail(int $id): Response
    {
        if (!isset($this->menus[$id])) {
            throw new NotFoundHttpException('Menu introuvable');
        }

        return $this->render('menu_detail.html.twig', [
            'menu' => $this->menus[$id]
        ]);
    }
}
