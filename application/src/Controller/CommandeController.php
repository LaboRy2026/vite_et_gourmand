<?php

namespace App\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CommandeController extends AbstractController
{
    #[Route('/commander/{id}', name: 'app_commander', requirements: ['id' => '\d+'])]
    public function commander(
        int $id,
        Request $request,
        Connection $connection
    ): Response {
        // Récupération du menu dans MariaDB
        $menuSql = $connection->fetchAssociative(
            'SELECT * FROM menu WHERE id_menu = ?',
            [$id]
        );

        if (!$menuSql) {
            throw $this->createNotFoundException('Menu introuvable');
        }

        $menu = [
            'titre' => $menuSql['titre'],
            'prix' => (float) $menuSql['prix_minimum'],
            'personnes' => (int) $menuSql['nb_personnes_min']
        ];

        if ($request->isMethod('POST')) {

            if (!$this->isCsrfTokenValid(
                'commander_' . $id,
                $request->request->getString('_token')
            )) {
                throw $this->createAccessDeniedException(
                    'Formulaire invalide'
                );
            }

            $nom = trim($request->request->getString('nom'));
            $email = trim($request->request->getString('email'));
            $datePrestation = $request->request->getString('date_prestation');
            $dateLivraison = $request->request->getString('date_livraison');
            $heureLivraison = $request->request->getString('heure_livraison');
            $adresse = trim($request->request->getString('adresse_livraison'));
            $ville = trim($request->request->getString('ville_livraison'));
            $personnes = $request->request->getInt('personnes');

                    $distanceSaisie = trim($request->request->getString('distance_livraison_km'));
                    $distance = filter_var($distanceSaisie, FILTER_VALIDATE_FLOAT);
                    $estBordeaux = mb_strtolower($ville) === 'bordeaux';

            $prestation = \DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $datePrestation
            );

            $livraison = \DateTimeImmutable::createFromFormat(
                '!Y-m-d',
                $dateLivraison
            );

            $datesValides =
                $prestation !== false
                && $livraison !== false
                && $prestation->format('Y-m-d') === $datePrestation
                && $livraison->format('Y-m-d') === $dateLivraison
                && $prestation >= new \DateTimeImmutable('today')
                && $livraison >= new \DateTimeImmutable('today')
                && $livraison <= $prestation;

            $heureValide = preg_match(
                '/^([01][0-9]|2[0-3]):[0-5][0-9]$/',
                $heureLivraison
            ) === 1;

            if (
                $nom === ''
                || mb_strlen($nom) > 150
                || !filter_var($email, FILTER_VALIDATE_EMAIL)
                || mb_strlen($email) > 255
                || !$datesValides
                || !$heureValide
                || $adresse === ''
                || $ville === ''
                || $personnes < $menu['personnes']

                    || (!$estBordeaux && (
                        $distanceSaisie === ''
                        || $distance === false
                        || $distance <= 0
                        || $distance > 1000
                    ))
            ) {
                $this->addFlash(
                    'danger',
                    'Vérifiez les informations de votre commande.'
                );
            } else {

                // Vérifier si l'e-mail existe déjà
                $existant = $connection->fetchOne(
                    'SELECT id_utilisateur FROM utilisateur WHERE email = ?',
                    [$email]
                );

                if ($existant) {
                    $this->addFlash(
                        'danger',
                        'Cet e-mail existe déjà. Pour ce test, utilisez une autre adresse fictive.'
                    );
                } else {

                    // Séparer le prénom et le nom
                    $parties = preg_split('/\s+/', $nom, 2);
                    $prenom = $parties[0];
                    $nomFamille = $parties[1] ?? '-';

                    // Mot de passe aléatoire non communiqué :
                    // compte de démonstration non connectable
                    $hash = password_hash(
                        bin2hex(random_bytes(24)),
                        PASSWORD_DEFAULT
                    );

                    
                    // Calcul du prix selon le nombre de convives
                    $prixMenu = round(
                        $menu['prix'] * $personnes / $menu['personnes'],
                        2
                    );

                    // Réduction de 10 % si au moins 5 personnes
                    // supplémentaires par rapport au minimum du menu
                    $reduction = 0.0;

                    if ($personnes >= $menu['personnes'] + 5) {
                        $reduction = round($prixMenu * 0.10, 2);
                    }
            
                    // Distance utilisée pour le calcul
                    $distanceLivraison = $estBordeaux ? 0.0 : (float) $distance;

                    // Livraison gratuite à Bordeaux
                    // Hors Bordeaux : 5 € + 0,59 € par kilomètre
                    $fraisLivraison = $estBordeaux
                        ? 0.0
                        : round(5 + 0.59 * $distanceLivraison, 2);

                    // Montant total de la commande
                    $prixTotal = round(
                        $prixMenu - $reduction + $fraisLivraison,
                        2
                    );
 

                    // Enregistrer le client et sa commande ensemble
                    $connection->beginTransaction();

                    try {
                        $connection->insert('utilisateur', [
                            'nom' => $nomFamille,
                            'prenom' => $prenom,
                            'email' => $email,
                            'mot_de_passe_hash' => $hash,
                            'role' => 'client',
                            'compte_actif' => 0
                        ]);

                        $idUtilisateur = (int) $connection->lastInsertId();

                        $connection->insert('commande', [
                            'id_utilisateur' => $idUtilisateur,
                            'id_menu' => $id,
                            'date_commande' => date('Y-m-d H:i:s'),
                            'date_prestation' => $datePrestation,
                            'date_livraison' => $dateLivraison,
                            'heure_livraison' => $heureLivraison,
                            'adresse_livraison' => $adresse,
                            'ville_livraison' => $ville,               
'nombre_personnes' => $personnes,
'prix_menu_commande' => $prixMenu,
'montant_reduction' => $reduction,
'distance_livraison_km' => $distanceLivraison,
'frais_livraison' => $fraisLivraison,
'prix_total' => $prixTotal,
'statut' => 'en_attente'
                        ]);

                        $idCommande = (int) $connection->lastInsertId();

                        $connection->commit();

                    } catch (\Throwable $e) {
                        $connection->rollBack();
                        throw $e;
                    }

                    $this->addFlash(
                        'success',
                        'Commande test n° ' . $idCommande
                        . ' enregistrée dans MariaDB !'
                    );

                    return $this->redirectToRoute(
                        'app_commander',
                        ['id' => $id]
                    );
                }
            }
        }

        return $this->render('commande.html.twig', [
            'menu' => $menu,
            'id' => $id
        ]);
    }
}
