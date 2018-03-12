-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 06, 2018 at 04:53 PM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `survey`
--

-- --------------------------------------------------------

--
-- Table structure for table `answers`
--

CREATE TABLE `answers` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `answer` text NOT NULL,
  `note` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `answers`
--

INSERT INTO `answers` (`id`, `survey_id`, `question_id`, `answer`, `note`) VALUES
(1, 1, 3, 'Ahmed', 'I am Ahmed'),
(2, 1, 4, '20', ''),
(3, 1, 6, 'Egypt', ''),
(4, 1, 3, 'Ahmed 2', ''),
(5, 1, 4, '30', 'I am 31'),
(6, 1, 6, 'Usa', ''),
(7, 1, 3, 'Kamel', 'I am A.Kamel'),
(8, 1, 4, '20', ''),
(9, 1, 6, 'Usa', ''),
(10, 1, 3, 'Joeffrey', ''),
(11, 1, 4, '30', 'Hello I am Joeffrey'),
(12, 1, 6, 'Egypt', ''),
(13, 1, 3, 'Google', ''),
(14, 1, 4, '30', ''),
(15, 1, 6, 'Usa', ''),
(16, 2, 2, 'Good', ''),
(17, 2, 5, 'Bad', ''),
(18, 2, 4, 'limitless', 'GOT'),
(19, 2, 2, 'Good', ''),
(20, 2, 5, 'Good', ''),
(21, 1, 3, 'A', ''),
(22, 1, 4, '20', ''),
(23, 1, 6, 'Usa', ''),
(25, 1, 3, 'B', ''),
(26, 1, 4, '40', ''),
(27, 1, 6, 'Canada', ''),
(28, 1, 3, 'C', ''),
(29, 1, 4, '30', ''),
(30, 1, 6, 'Canada', ''),
(31, 1, 3, 'D', ''),
(32, 1, 4, '60', ''),
(33, 1, 6, 'Egypt', ''),
(34, 1, 3, 'E', ''),
(35, 1, 4, '30', ''),
(36, 1, 6, 'Egypt', ''),
(37, 1, 3, 'H', ''),
(38, 1, 4, '50', ''),
(39, 1, 6, 'Usa', ''),
(40, 1, 3, 'Q', ''),
(41, 1, 4, '30', ''),
(42, 1, 6, 'Egypt', ''),
(43, 1, 3, 'W', ''),
(44, 1, 4, '30', ''),
(45, 1, 6, 'Egypt', ''),
(46, 1, 3, 'F', ''),
(47, 1, 4, '30', ''),
(48, 1, 6, 'Egypt', ''),
(49, 1, 3, 'M', ''),
(50, 1, 4, '20', ''),
(51, 1, 6, 'Usa', ''),
(52, 1, 3, 'V', ''),
(53, 1, 4, '30', ''),
(54, 1, 6, 'Canada', ''),
(55, 1, 3, 'K', ''),
(56, 1, 4, '50', ''),
(57, 1, 6, 'Egypt', ''),
(58, 1, 3, 'U', ''),
(59, 1, 4, '20', ''),
(60, 1, 6, 'Egypt', ''),
(61, 1, 3, 'Z', ''),
(62, 1, 4, '30', ''),
(63, 1, 6, 'Canada', '');

-- --------------------------------------------------------

--
-- Table structure for table `fields`
--

CREATE TABLE `fields` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `field_id` int(11) NOT NULL,
  `field_type` varchar(191) NOT NULL,
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `fields`
--

INSERT INTO `fields` (`id`, `survey_id`, `field_id`, `field_type`, `title`) VALUES
(30, 1, 1, 'instruction', 'Instruction: Please Answer below Questions'),
(31, 1, 2, 'explanation', 'Some personal question:'),
(32, 1, 3, 'question', 'What is your name?'),
(33, 1, 4, 'question', 'what is your age?'),
(34, 1, 5, 'explanation', 'Select correct answer:'),
(35, 1, 6, 'question', 'Where are you from ... (edited)?'),
(42, 2, 1, 'instruction', 'This is survey 2'),
(43, 2, 2, 'question', 'What do you think??'),
(44, 2, 5, 'question', 'How are you?'),
(45, 2, 3, 'explanation', 'Explain This:'),
(46, 2, 4, 'question', 'What is your fav movie?'),
(47, 2, 1414, 'question', 'New Question2?'),
(48, 2, 2568, 'loaded', 'Bye');

-- --------------------------------------------------------

--
-- Table structure for table `questions_meta`
--

CREATE TABLE `questions_meta` (
  `id` int(11) NOT NULL,
  `survey_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `field_name` varchar(191) DEFAULT NULL,
  `summarize` int(11) NOT NULL,
  `type` varchar(191) NOT NULL,
  `type_fields` text,
  `chart` varchar(191) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `questions_meta`
--

INSERT INTO `questions_meta` (`id`, `survey_id`, `question_id`, `field_name`, `summarize`, `type`, `type_fields`, `chart`) VALUES
(16, 1, 3, 'q_name', 1, 'open-text', '', 'table-analysis'),
(17, 1, 4, 'q_age', 1, 'radio', '20,30,40,50,60', 'pie-chart'),
(18, 1, 6, 'q_city', 1, 'select', 'Usa,Egypt,Canada', 'bar-chart'),
(22, 2, 2, '', 1, 'radio', 'Good,Bad', 'bar-chart'),
(23, 2, 5, '', 1, 'select', 'Good,Bad,Not Good', 'pie-chart'),
(24, 2, 4, '', 1, 'short-text', '', 'list-analysis'),
(25, 2, 1414, '', 1, 'radio', 'jhjh,kkk', 'table-analysis');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `answers`
--
ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `fields`
--
ALTER TABLE `fields`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions_meta`
--
ALTER TABLE `questions_meta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `answers`
--
ALTER TABLE `answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;
--
-- AUTO_INCREMENT for table `fields`
--
ALTER TABLE `fields`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;
--
-- AUTO_INCREMENT for table `questions_meta`
--
ALTER TABLE `questions_meta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
