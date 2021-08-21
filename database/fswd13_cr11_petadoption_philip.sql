-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 21, 2021 at 03:52 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 8.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fswd13_cr11_petadoption_philip`
--
CREATE DATABASE IF NOT EXISTS `fswd13_cr11_petadoption_philip` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `fswd13_cr11_petadoption_philip`;

-- --------------------------------------------------------

--
-- Table structure for table `animals`
--

CREATE TABLE `animals` (
  `id` int(11) UNSIGNED NOT NULL,
  `picture` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `location` varchar(100) NOT NULL,
  `description` text DEFAULT 'Lorem ipsum dolor sit amet.',
  `size` enum('small','large') DEFAULT 'small',
  `age` int(11) UNSIGNED NOT NULL,
  `hobbies` text DEFAULT 'Consetetur sadipscing elitr, sed diam.',
  `breed` varchar(100) NOT NULL,
  `status` enum('free','adopted') DEFAULT 'free'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `animals`
--

INSERT INTO `animals` (`id`, `picture`, `name`, `location`, `description`, `size`, `age`, `hobbies`, `breed`, `status`) VALUES
(1, '611fda2b10c3b.jfif', 'Mousse', 'China Town 1235', 'Sometimes transforms into a boy!', 'small', 3, 'fighting with Ranma', 'Nannichuan', 'free'),
(2, '611fda75153ec.jpg', 'Luzifer', 'Kleinengersdorferstraße 6/5/3, 2100 Korneuburg', 'Best Cat Ever, But Mean!', 'small', 12, 'sleep, eat, be mean', 'Bombay cat', 'adopted'),
(3, '611fdbd166155.jpg', 'Don Don', 'Margarethen am Moos', 'Likes being outside', 'small', 18, 'sleep, eat, run', 'Some breed', 'adopted'),
(4, '611fdbea4ba8d.png', 'Hamtaro', 'Anime Town 123', 'An old Hamster who once was famous', 'small', 10, 'Helping others', 'Anime', 'free'),
(5, '611fdb63cd2b0.jfif', 'P-Chan', 'In Akane\'s room upstairs!', 'A small pig that sometimes transforms into a boy', 'small', 4, 'Likes getting lost', 'Nannichuan', 'adopted'),
(6, '611fdb74baacf.jfif', 'Genma', 'In Soun\'s House', 'An old Panda that sometimes becomes a man', 'large', 36, 'Ignoring trouble &amp; pretending not to be there', 'Nannichuan', 'free'),
(7, '6120fc841c52a.jfif', 'Benjamin', 'In a zoo', 'Benjamin the Elephant is an animated children\'s television show produced by Kiddinx Studios in Berlin.', 'large', 0, 'He loves lazing around', 'Human-like', 'free'),
(8, '611fdb9f0647b.jpg', 'Gio', 'Zoo City 21/08', 'A giraffe that is... tall', 'large', 9, 'being watched', 'Nubian giraffe', 'free'),
(9, '611fdbaa14585.jpg', 'Bacon', 'Pig Town 08/21', 'A kind pig named Bacon', 'large', 5, 'Sleeping &amp; eating', 'Boar', 'free'),
(10, '611fdbb5e2856.jfif', 'Miltank', 'Routes 38 and 39', 'Miltank (Japanese: ミルタンク Miltank) is a Normal-type Pokémon introduced in Generation II.', 'large', 55, '[value-8]', 'bipedal, bovine Pokémon', 'adopted'),
(17, 'animal.png', 'Testy', 'Testy', 'Testy', 'small', 5, 'Testy', 'Testy', 'free');

-- --------------------------------------------------------

--
-- Table structure for table `pet_adoption`
--

CREATE TABLE `pet_adoption` (
  `id` int(11) UNSIGNED NOT NULL,
  `fk_user_id` int(11) UNSIGNED DEFAULT NULL,
  `fk_animal_id` int(11) UNSIGNED DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pet_adoption`
--

INSERT INTO `pet_adoption` (`id`, `fk_user_id`, `fk_animal_id`, `date`) VALUES
(12, 2, 10, '2021-08-23'),
(13, 2, 2, '2021-08-29'),
(14, 4, 5, '2021-08-22');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `address` varchar(100) NOT NULL,
  `picture` varchar(100) NOT NULL,
  `status` enum('user','adm') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `first_name`, `last_name`, `email`, `password`, `phone_number`, `address`, `picture`, `status`) VALUES
(2, 'Elliot', 'Reid', 'er@mail.de', '$2y$10$q1jR3dOtVURdXql4.HN3t.hanRsVqIQp6BOqnU0VsMzGiExpnmybW', '+431234567890', 'Scrubs Street 170, 2010 US und A', '612099bcbee99.png', 'user'),
(3, 'Philip', 'Mahlberg', 'pm@mail.de', '$2y$10$NrxH4e4zqLpTu.w129McTuDknknKazogxEZNrIiy/tNLuXdgcHBTu', '+4366418201134', 'Haidengasse 5', 'admavatar.png', 'adm'),
(4, 'Turk', 'Turkleton', 'ck@mail.de', '$2y$10$j/31oBw6nhm9OMHXF7Ql8OnWiE58T6oUAREe8EHEO20Ot.n2GQc9W', '+009162255887', 'New Sacred Heart Hospital', '61210188a5e69.png', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `animals`
--
ALTER TABLE `animals`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`fk_user_id`),
  ADD KEY `fk_animal_id` (`fk_animal_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `animals`
--
ALTER TABLE `animals`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pet_adoption`
--
ALTER TABLE `pet_adoption`
  ADD CONSTRAINT `pet_adoption_ibfk_1` FOREIGN KEY (`fk_user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pet_adoption_ibfk_2` FOREIGN KEY (`fk_animal_id`) REFERENCES `animals` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
