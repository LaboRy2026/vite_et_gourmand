<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/commander/{id}', name: 'app_commander', requirements: ['id' => '\d+'])]
    public function commander(int $id, Request $request): Response
    {
        $menus = [
            1 => ['titre' => 'Menu de Noël Tradition', 'prix' => 180, 'personnes' => 6],
            2 => ['titre' => 'Menu Mariage Élégance', 'prix' => 600, 'personnes' => 20],
            3 => ['titre' => 'Menu Jardin Végétarien', 'prix' => 150, 'personnes' => 6]
        ];

        if (!isset($menus[$id])) {
            throw $this->createNotFoundException('Menu introuvable');
        }

        $menu = $menus[$id];

        if ($request->isMethod('POST')) {
            if (!$this->isCsrfTokenValid(
                'commander_' . $id,
                $request->request->getString('_token')
            )) {
                throw $this->createAccessDeniedException('Formulaire invalide');
            }

            $nom = trim($request->request->getString('nom'));
            $email = trim($request->request->getString('email'));
            $date = $request->request->getString('date_prestation');
            $personnes = $request->request->getInt('personnes');

            $dateValide = \DateTimeImmutable::createFromFormat('!Y-m-d', $date);
            $dateCorrecte = $dateValide
                && $dateValide->format('Y-m-d') === $date
                && $dateValide >= new \DateTimeImmutable('today');

            if (
                $nom === ''
                || !filter_var($email, FILTER_VALIDATE_EMAIL)
                || !$dateCorrecte
                || $personnes < $menu['personnes']
            ) {
                $this->addFlash('danger', 'Vérifiez les informations saisies.');
            } else {
                $this->addFlash(
                    'success',
                    'Formulaire validé pour ' . $menu['titre']
                    . '. Ceci est une simulation : aucune commande n’a encore été enregistrée.'
                );

                return $this->redirectToRoute('app_commander', ['id' => $id]);
            }
        }

        return $this->render('commande.html.twig', [
            'menu' => $menu,
            'id' => $id
        ]);
    }
}
