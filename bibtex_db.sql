-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 15 Lut 2021, 20:17
-- Wersja serwera: 10.4.14-MariaDB
-- Wersja PHP: 7.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `bibtex_db`
--

CREATE DATABASE 'bibtex_db';
USE 'bibtex_db';

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `deleteResources` (IN `abook_id` INT)  MODIFIES SQL DATA
BEGIN
DELETE FROM book_resources WHERE book_resources.bookID = abook_id;
DELETE FROM books WHERE ID = abook_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `get` ()  READS SQL DATA
select * from books$$

DELIMITER ;

-- --------------------------------------------------------

CREATE TABLE `books` (
  `ID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL DEFAULT 'n/a',
  `publisher` varchar(255) NOT NULL DEFAULT 'n/a',
  `year` varchar(30) NOT NULL,
  `category` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `book_resources` (
  `ID` int(11) NOT NULL,
  `bookID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL,
  `link` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `categories` (
  `categoryID` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `categories`
--

INSERT INTO `categories` (`categoryID`, `name`) VALUES
(1, 'matematyka'),
(2, 'sieci komputerowe'),
(3, 'teoria grafów'),
(4, 'sztuczna inteligencja'),
(5, 'elektronika'),
(6, 'kryptografia'),
(7, 'robotyka'),
(8, 'socjologia'),
(9, 'nieokreślona');


CREATE TABLE `resource_category` (
  `ID` int(11) NOT NULL,
  `tagName` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `resource_category` (`ID`, `tagName`) VALUES
(1, 'Other'),
(2, 'Article'),
(3, 'Website');

--
-- Indeksy dla tabeli `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_cat` (`category`);

--
-- Indeksy dla tabeli `book_resources`
--
ALTER TABLE `book_resources`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_tag` (`tagID`),
  ADD KEY `fk_bookid` (`bookID`);

--
-- Indeksy dla tabeli `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categoryID`);

--
-- Indeksy dla tabeli `resource_category`
--
ALTER TABLE `resource_category`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT dla tabeli `books`
--
ALTER TABLE `books`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT dla tabeli `book_resources`
--
ALTER TABLE `book_resources`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT dla tabeli `categories`
--
ALTER TABLE `categories`
  MODIFY `categoryID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT dla tabeli `resource_category`
--
ALTER TABLE `resource_category`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Ograniczenia dla tabeli `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `fk_cat` FOREIGN KEY (`category`) REFERENCES `categories` (`categoryID`);

--
-- Ograniczenia dla tabeli `book_resources`
--
ALTER TABLE `book_resources`
  ADD CONSTRAINT `fk_bookid` FOREIGN KEY (`bookID`) REFERENCES `books` (`ID`),
  ADD CONSTRAINT `fk_tag` FOREIGN KEY (`tagID`) REFERENCES `resource_category` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
