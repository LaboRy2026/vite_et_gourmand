
# Vite & Gourmand

## Présentation du projet

Vite & Gourmand est une application web réalisée dans le cadre de l'évaluation en cours de formation (ECF) du titre professionnel Développeur Web et Web Mobile (DWWM).

Le projet répond à un cahier des charges concernant une entreprise de traiteur située à Bordeaux.

L'objectif est de permettre aux visiteurs de consulter les menus proposés par le traiteur, de découvrir leurs caractéristiques et de transmettre une commande. Une interface d'administration permet de consulter les commandes enregistrées et de modifier leur statut.

## Site en ligne

https://vitegourmand2026.alwaysdata.net

## Dépôt GitHub

https://github.com/LaboRy2026/vite_et_gourmand

## Technologies utilisées

- HTML5 et CSS3 : structure et présentation des pages
- Bootstrap : mise en page responsive
- PHP 8.4 : développement côté serveur
- Symfony 7.4 : framework PHP
- Twig : moteur de templates
- MariaDB : base de données relationnelle
- SQL : création et interrogation des données
- Composer : gestion des dépendances PHP
- Git et GitHub : gestion de versions
- GitHub Codespaces : environnement de développement
- alwaysdata : hébergement de l'application

## Fonctionnalités réalisées

- Page d'accueil présentant Vite & Gourmand
- Consultation d'un catalogue de menus
- Affichage de fiches détaillées des menus
- Formulaire de simulation et d'enregistrement de commande
- Enregistrement des commandes dans MariaDB
- Interface d'administration avec authentification HTTP Basic
- Consultation des commandes
- Modification du statut d'une commande
- Vérification des données avec des commandes SQL

Les principales fonctionnalités ont été testées sur l'hébergement alwaysdata.

## Organisation du projet

Le code de l'application Symfony se trouve dans le dossier `application/`.

Principaux dossiers :

- `application/src/Controller/` : contrôleurs Symfony
- `application/templates/` : pages Twig
- `application/public/` : fichiers publics et point d'entrée du site
- `application/config/` : configuration Symfony
- `application/docs/` : documentation et captures, lorsqu'elles sont présentes dans le dépôt

Le fichier SQL du projet est situé dans `application/vite_et_gourmand.sql`.

## Prérequis pour une installation locale

- PHP 8.4 avec les extensions nécessaires à Symfony et à MariaDB
- Composer
- Un serveur MariaDB ou MySQL
- Git
- Un terminal

## Installation locale

### 1. Récupérer le projet

```bash
git clone https://github.com/LaboRy2026/vite_et_gourmand.git
cd vite_et_gourmand/application
```

### 2. Installer les dépendances

```bash
composer install
```

### 3. Préparer la base de données

Créer une base MariaDB nommée `vite_et_gourmand`.

Importer ensuite le script SQL fourni avec le projet depuis un outil tel que phpMyAdmin ou le client MariaDB.

Exemple :

```bash
mariadb -u root -p vite_et_gourmand < vite_et_gourmand.sql
```

Les données de démonstration doivent être présentes dans la base pour tester les fonctionnalités.

### 4. Configurer les variables d'environnement

Créer un fichier `.env.local` dans le dossier `application/`.

Exemple de configuration :

```dotenv
APP_ENV=dev
APP_DEBUG=1
APP_SECRET=remplacer_par_une_cle_secrete
DATABASE_URL="mysql://utilisateur:mot_de_passe@127.0.0.1:3306/vite_et_gourmand?charset=utf8mb4"
ADMIN_DEMO_PASSWORD="remplacer_par_un_mot_de_passe_admin"
```

Remplacer les valeurs d'exemple par celles de l'environnement local.

Le fichier `.env.local` contient des informations confidentielles et ne doit pas être publié sur GitHub.

### 5. Démarrer l'application

Depuis le dossier `application/`, exécuter :

```bash
php -S 127.0.0.1:8000 -t public public/index.php
```

Ouvrir ensuite dans le navigateur :

http://127.0.0.1:8000

## Administration

L'espace d'administration est accessible à l'adresse :

`/admin/commandes`

Identifiant : `admin`

Le mot de passe doit être défini dans la variable d'environnement `ADMIN_DEMO_PASSWORD`.

Aucun mot de passe réel n'est publié dans ce dépôt.

## Base de données

Le projet s'appuie sur une base relationnelle MariaDB composée de 14 tables.

Elle comprend notamment les informations relatives aux utilisateurs, aux menus, aux plats, aux allergènes, aux commandes, aux avis et à l'historique des statuts.

Le projet a également fait l'objet d'une étude de stockage NoSQL pour les statistiques.

## Déploiement

L'application est hébergée sur alwaysdata.

Configuration utilisée :

- Environnement Symfony : `prod`
- Débogage désactivé
- PHP 8.4
- Base MariaDB hébergée sur alwaysdata
- Répertoire web : `application/public/`
- Connexion HTTPS

Les paramètres confidentiels sont conservés dans le fichier `.env.local` sur le serveur.

## Tests effectués

- Affichage de la page d'accueil
- Affichage des trois menus de démonstration
- Consultation d'une fiche de menu
- Affichage du formulaire de commande
- Enregistrement d'une commande test dans MariaDB
- Connexion à l'administration
- Consultation des commandes enregistrées
- Modification du statut d'une commande en « Acceptée »

Ces tests ont été réalisés sur le site déployé.

## Limites de cette version

Cette version constitue une réalisation partielle du cahier des charges initial.

Les fonctionnalités avancées telles que l'espace client complet, les filtres dynamiques, les notifications automatiques par e-mail, la gestion des employés et les statistiques NoSQL intégrées ne sont pas encore opérationnelles.

Le formulaire de commande est utilisé dans le cadre d'une démonstration pédagogique, sans paiement en ligne.

## Contexte

Projet réalisé dans le cadre de la formation Développeur Web et Web Mobile chez Studi.

Évaluation ECF — Novembre 2026.
