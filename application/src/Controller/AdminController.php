<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/commandes', name: 'app_admin_commandes')]
    public function commandes(
        Request $request,
        Connection $connection
    ): Response {
        // Vérification de l'accès administrateur
        $motDePasse = $_SERVER['ADMIN_DEMO_PASSWORD']
            ?? $_ENV['ADMIN_DEMO_PASSWORD']
            ?? '';

        $autorise = $request->getUser() === 'admin'
            && $motDePasse !== ''
            && hash_equals(
                $motDePasse,
                (string) $request->getPassword()
            );

        if (!$autorise) {
            return new Response(
                'Authentification administrateur requise.',
                Response::HTTP_UNAUTHORIZED,
                [
                    'WWW-Authenticate' => 'Basic realm="Administration Vite et Gourmand"',
                    'Cache-Control' => 'no-store'
                ]
            );
        }

        // Modification du statut
        if ($request->isMethod('POST')) {
            $id = $request->request->getInt('id_commande');
            $statut = $request->request->getString('statut');

            $statutsAutorises = [
                'en_attente',
                'acceptee',
                'en_preparation',
                'terminee',
                'annulee'
            ];

            if (!$this->isCsrfTokenValid(
                'statut_' . $id,
                $request->request->getString('_token')
            )) {
                throw $this->createAccessDeniedException(
                    'Formulaire invalide'
                );
            }

            if ($id > 0 && in_array($statut, $statutsAutorises, true)) {
                $lignes = $connection->update(
                    'commande',
                    [
                        'statut' => $statut,
                        'date_modification' => date('Y-m-d H:i:s')
                    ],
                    ['id_commande' => $id]
                );

                if ($lignes > 0) {
                    $this->addFlash(
                        'success',
                        'Statut de la commande n° ' . $id . ' modifié.'
                    );
                } else {
                    $this->addFlash(
                        'danger',
                        'Commande introuvable.'
                    );
                }
            } else {
                $this->addFlash(
                    'danger',
                    'Statut ou numéro de commande invalide.'
                );
            }

            return $this->redirectToRoute('app_admin_commandes');
        }

        // Consultation des commandes
        $commandes = $connection->fetchAllAssociative(
            'SELECT c.id_commande,
                    c.date_prestation,
                    c.nombre_personnes,
                    c.prix_total,
                    c.statut,
                    m.titre AS menu,
                    u.nom,
                    u.prenom
             FROM commande c
             JOIN menu m ON m.id_menu = c.id_menu
             JOIN utilisateur u ON u.id_utilisateur = c.id_utilisateur
             ORDER BY c.id_commande DESC'
        );

        $reponse = $this->render('admin_commandes.html.twig', [
            'commandes' => $commandes
        ]);

        $reponse->headers->set('Cache-Control', 'private, no-store');

        return $reponse;
    }
}
