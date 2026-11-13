<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(Connection $connection): Response
    {
        $avis = $connection->fetchAllAssociative(
            "SELECT note, commentaire, date_avis
             FROM avis
             WHERE statut_validation = :statut
             ORDER BY date_avis DESC, id_avis DESC
             LIMIT 5",
            ['statut' => 'valide']
        );

        return $this->render('accueil.html.twig', [
            'avis_valides' => $avis
        ]);
    }
}
