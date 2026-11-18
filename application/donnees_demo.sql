-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: mysql-vitegourmand2026.alwaysdata.net
-- Generation Time: Nov 18, 2026 at 02:50 PM
-- Server version: 11.8.9-MariaDB
-- PHP Version: 8.4.27

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vitegourmand2026_vite_et_gourmand`
--

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id_menu`, `titre`, `description`, `theme`, `regime`, `nb_personnes_min`, `prix_minimum`, `stock_disponible`, `conditions`) VALUES
(1, 'Menu de Noël Tradition', 'Un repas festif pour partager un moment gourmand.', 'Noël', 'Classique', 6, 180.00, 20, 'Commander au moins 72 heures à l avance.'),
(2, 'Menu Mariage Élégance', 'Une sélection raffinée pour votre réception.', 'Mariage', 'Classique', 20, 600.00, 10, 'Réservation à anticiper.'),
(3, 'Menu Jardin Végétarien', 'Des recettes végétariennes gourmandes.', 'Toutes occasions', 'Végétarien', 6, 150.00, 15, 'Disponibilité à confirmer.');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
