-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Czas generowania: 19 Maj 2021, 21:02
-- Wersja serwera: 8.0.22
-- Wersja PHP: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `wd`
--

DELIMITER $$
--
-- Procedury
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `prowadzacy_lista` (IN `a` VARCHAR(5), IN `b` VARCHAR(5), IN `c` VARCHAR(5))  NO SQL
BEGIN
DECLARE A varchar(5) DEFAULT a;
DECLARE B varchar(5) DEFAULT b;
DECLARE C varchar(5) DEFAULT c;

IF a="all" THEN
	set A='%';
else 
	set A=a;
END IF;


IF b="all" THEN
	set B='%';
else 
	set B=b;
END IF;

IF c="all" THEN
	set C='%';
else 
	set C=c;
END IF;


SELECT kierunki_przedmioty_prowadzacy.ID,kierunki.kierunek,przedmioty.nazwa,przedmioty.ID As przedmiot_id,kierunki_przedmioty.kierunek_id,kierunki_przedmioty.ETC,kierunki_przedmioty.semestr, kierunki_przedmioty_prowadzacy.prowadzacy_id,prowadzacy.imie,prowadzacy.nazwisko
from kierunki_przedmioty inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID
inner join przedmioty on przedmioty.ID=kierunki_przedmioty.przedmiot_id 
inner join kierunki on kierunki.ID=kierunki_przedmioty.kierunek_id
inner join prowadzacy on prowadzacy.ID=kierunki_przedmioty_prowadzacy.prowadzacy_id

where kierunki_przedmioty_prowadzacy.prowadzacy_id like A
AND przedmioty.ID like B
AND kierunki_przedmioty.kierunek_id like C;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `student_przedmiot` (IN `a` INT(5))  READS SQL DATA
select kierunki_przedmioty_prowadzacy.ID, kierunki_przedmioty.przedmiot_id,kierunki_przedmioty.przedmiot_id,studenci_kierunki.student_id from kierunki_przedmioty inner join studenci_kierunki on studenci_kierunki.kierunek_id=kierunki_przedmioty.kierunek_id
inner join kierunki_przedmioty_prowadzacy on kierunki_przedmioty_prowadzacy.kierunki_przedmioty_id=kierunki_przedmioty.ID where studenci_kierunki.student_id =a$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kierunki`
--

CREATE TABLE `kierunki` (
  `ID` int NOT NULL,
  `kierunek` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `wydzial_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `kierunki`
--

INSERT INTO `kierunki` (`ID`, `kierunek`, `wydzial_id`) VALUES
(1, 'Informatyka Techniczna', 3),
(2, 'Cyberbezpieczeństwo', 3),
(3, 'Inżynieria Oprogramowania', 3),
(4, 'Fizyka Stosowana', 1),
(5, 'Fizyka Jądrowa', 1),
(6, 'Metaloznasctwo', 2),
(7, 'Automatyka', 4),
(8, 'Robotyka', 4),
(9, 'Geologia Stosowana', 5),
(10, 'Górnictwo', 5),
(12, 'Socjologia', 7);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kierunki_przedmioty`
--

CREATE TABLE `kierunki_przedmioty` (
  `ID` int NOT NULL,
  `kierunek_id` int NOT NULL,
  `przedmiot_id` int NOT NULL,
  `ETC` tinyint NOT NULL,
  `semestr` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `kierunki_przedmioty`
--

INSERT INTO `kierunki_przedmioty` (`ID`, `kierunek_id`, `przedmiot_id`, `ETC`, `semestr`) VALUES
(1, 1, 1, 5, 1),
(3, 1, 9, 4, 1),
(6, 1, 10, 6, 2),
(7, 2, 4, 5, 1),
(8, 2, 5, 6, 1),
(9, 2, 10, 3, 2),
(10, 2, 12, 4, 2),
(11, 2, 9, 5, 3),
(12, 3, 11, 3, 1),
(13, 3, 7, 6, 1),
(14, 3, 10, 6, 2),
(15, 3, 9, 4, 2),
(16, 4, 1, 4, 1),
(17, 4, 2, 5, 2),
(18, 4, 4, 3, 2),
(19, 4, 5, 4, 3),
(20, 5, 1, 4, 1),
(21, 5, 2, 5, 1),
(22, 5, 4, 4, 2),
(23, 5, 5, 3, 3),
(24, 5, 3, 5, 3),
(25, 6, 1, 3, 1),
(26, 6, 2, 4, 1),
(27, 6, 3, 5, 2),
(28, 6, 8, 6, 2),
(29, 7, 1, 4, 1),
(30, 7, 7, 3, 2),
(31, 7, 9, 5, 2),
(32, 8, 1, 6, 1),
(33, 8, 7, 3, 1),
(34, 8, 12, 4, 2),
(35, 9, 1, 3, 1),
(36, 9, 15, 4, 1),
(37, 9, 13, 4, 2),
(38, 10, 1, 5, 1),
(39, 10, 14, 3, 2),
(40, 12, 16, 4, 1),
(41, 12, 17, 5, 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kierunki_przedmioty_prowadzacy`
--

CREATE TABLE `kierunki_przedmioty_prowadzacy` (
  `ID` int NOT NULL,
  `kierunki_przedmioty_id` int NOT NULL,
  `prowadzacy_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `kierunki_przedmioty_prowadzacy`
--

INSERT INTO `kierunki_przedmioty_prowadzacy` (`ID`, `kierunki_przedmioty_id`, `prowadzacy_id`) VALUES
(1, 1, 5),
(2, 3, 3),
(3, 6, 4),
(4, 7, 6),
(5, 8, 4),
(6, 9, 6),
(7, 10, 7),
(8, 11, 4),
(9, 12, 8),
(10, 13, 5),
(11, 14, 9),
(12, 15, 10),
(13, 16, 7),
(14, 17, 3),
(15, 18, 8),
(16, 19, 6),
(17, 20, 7),
(18, 21, 3),
(19, 22, 6),
(20, 23, 6),
(21, 24, 10),
(22, 25, 7),
(23, 26, 3),
(24, 27, 10),
(25, 28, 10),
(26, 29, 2),
(27, 30, 5),
(28, 31, 3),
(29, 32, 2),
(30, 33, 6),
(31, 34, 1),
(32, 35, 2),
(33, 36, 9),
(34, 37, 2),
(35, 38, 2),
(36, 39, 11),
(37, 40, 13),
(38, 41, 13);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `pracownicy`
--

CREATE TABLE `pracownicy` (
  `ID` int NOT NULL,
  `imie` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `nazwisko` varchar(30) COLLATE utf8_polish_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `wydzial_id` int NOT NULL,
  `super_admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `pracownicy`
--

INSERT INTO `pracownicy` (`ID`, `imie`, `nazwisko`, `mail`, `haslo`, `wydzial_id`, `super_admin`) VALUES
(1, 'Edmund', 'Kruk', 'ekruk@gmail.com', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G', 1, 1),
(2, 'Jakub', 'Sołtys', 'jsoltys@gmail.com', '$2y$10$GZCqb0uo3cuqLGvuxH/VgusOTvITPvrV00xjFUJchRDku/LnVAPgi', 2, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `prowadzacy`
--

CREATE TABLE `prowadzacy` (
  `ID` int NOT NULL,
  `imie` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `nazwisko` varchar(30) COLLATE utf8mb4_polish_ci NOT NULL,
  `mail` varchar(50) COLLATE utf8mb4_polish_ci NOT NULL,
  `haslo` varchar(100) COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `prowadzacy`
--

INSERT INTO `prowadzacy` (`ID`, `imie`, `nazwisko`, `mail`, `haslo`) VALUES
(1, ' Mariusz', 'Strojny', 'mstrojny@gmail.com', '$2y$10$O36WaWKehpf52w1dgYd6vuUmYFHOcmoN4is/pJ0uu6WcnqdpW15mu'),
(2, 'Anna', 'Kot', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(3, 'Julia', 'Sikora', 'c', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(4, 'Anna', 'Ptasznik', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(5, 'Stefan', 'Witos', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(6, 'Kamil', 'Kowal', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(7, 'Jakub', 'Stefaniak', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(8, 'Mikołaj', 'Zbroja', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(9, 'Kinga', 'Kozioł', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(10, 'Maria', 'Król', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(11, 'Arkadiusz', 'Bednarczyk', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(12, 'Ewa', 'Kwiatkowska', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G'),
(13, 'Tomasz', 'Wilk', 'b', '$2y$10$i213tuU74r5XGJ4GwrZBoe4Jw6LQwUQ7x1c5MdjqfwLZCOqBYL91G');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmioty`
--

CREATE TABLE `przedmioty` (
  `ID` int NOT NULL,
  `nazwa` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `przedmioty`
--

INSERT INTO `przedmioty` (`ID`, `nazwa`) VALUES
(1, 'Podstawy Fizyki'),
(2, 'Mechanika Płynów'),
(3, 'Podstawy mechniki ciała stałego'),
(4, 'Analiza Matematyczna'),
(5, 'Równania różniczkowe'),
(6, 'Inżynieria materiałowa'),
(7, 'Matematyka'),
(8, 'Wytrzymałość metali'),
(9, 'Podstawy informatyki'),
(10, 'Programowanie obiektowe'),
(11, 'Algorytmy'),
(12, 'Elektronika'),
(13, 'Podstawy Geologi'),
(14, 'Górnictwo i Turystyka'),
(15, 'Podstawy matematyki'),
(16, 'Wiedza o kulturze'),
(17, 'Podstawy negocjacji');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studenci`
--

CREATE TABLE `studenci` (
  `ID` int NOT NULL,
  `imie` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `nazwisko` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `telefon` bigint NOT NULL,
  `nr_album` int NOT NULL,
  `haslo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `mail` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `studenci`
--

INSERT INTO `studenci` (`ID`, `imie`, `nazwisko`, `telefon`, `nr_album`, `haslo`, `mail`) VALUES
(3, 'Ala', 'Misiek', 123456788, 100003, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(4, 'Jakub', 'Michalik', 234567897, 100004, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(5, 'Jedrzej', 'Kus', 123456786, 100005, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(6, 'Dorota', 'Stolarz', 234567895, 100006, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'dorta@gmail.com'),
(7, 'Joanna', 'Kot', 123456784, 100007, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(8, 'Anna', 'Jasinka', 234567893, 100008, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(9, 'Michał', 'Wójt', 223456789, 100009, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(10, 'Ivan', 'Groźny', 334567890, 100010, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(11, 'paweł', 'Wandas', 423456788, 100011, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(12, 'Aleksandra', 'Wojtasik', 534567897, 100012, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(13, 'Kamil', 'Kozioł', 623456786, 100013, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(14, 'Ewa', 'Jakubowicz', 734567895, 100014, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(15, 'Marzena', 'Wisołowska', 823456784, 100015, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(16, 'Iga', 'Świątek', 934567893, 100016, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(17, 'Elwis', 'Presley', 223856789, 100017, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(18, 'Krzysztof', 'Gut', 314567890, 100018, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(19, 'Maryla', 'Rodowicz', 423450788, 100019, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(20, 'Doda', 'Elektroda', 574567897, 100020, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(21, 'Jan', 'Janko', 683456786, 100021, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(22, 'Krzysztof', 'Ibisz', 794567895, 100022, '$2y$10$ixtOpwqK4Doyo5ZXfqRysefbmc7D.tXc4t0/GfhYV5FRizoNRZqNy', 'a'),
(47, 'Jan', 'Sobieski', 384725962, 100024, '$2y$10$0L31Plc9/D3HjQpUtrQoJOInzCgML38VLcUgVbah8BLPoPWQ5.tC6', 'js@gmail.com');

--
-- Wyzwalacze `studenci`
--
DELIMITER $$
CREATE TRIGGER `delete_oceny` AFTER DELETE ON `studenci` FOR EACH ROW DELETE from studenci_oceny WHERE student_id
=old.ID
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studenci_kierunki`
--

CREATE TABLE `studenci_kierunki` (
  `ID` int NOT NULL,
  `kierunek_id` int NOT NULL,
  `student_id` int NOT NULL,
  `semestr` tinyint NOT NULL,
  `rok_rozpoczecia` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `studenci_kierunki`
--

INSERT INTO `studenci_kierunki` (`ID`, `kierunek_id`, `student_id`, `semestr`, `rok_rozpoczecia`) VALUES
(3, 3, 3, 1, 2020),
(4, 2, 4, 1, 2020),
(5, 2, 5, 1, 2020),
(6, 5, 6, 1, 2020),
(7, 6, 6, 3, 2019),
(8, 2, 8, 1, 2020),
(9, 7, 9, 3, 2019),
(10, 3, 9, 1, 2020),
(11, 8, 10, 1, 2020),
(12, 1, 11, 1, 2020),
(13, 4, 12, 1, 2020),
(14, 5, 13, 2, 2020),
(15, 6, 14, 1, 2020),
(16, 9, 15, 1, 2020),
(17, 9, 16, 1, 2020),
(18, 10, 17, 3, 2019),
(19, 7, 18, 1, 2020),
(20, 8, 19, 1, 2020),
(21, 10, 20, 1, 2020),
(22, 8, 21, 3, 2019),
(23, 4, 22, 2, 2020),
(26, 3, 7, 1, 2020),
(53, 12, 3, 1, 2021),
(54, 5, 21, 1, 2021),
(56, 8, 47, 1, 2021);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `studenci_oceny`
--

CREATE TABLE `studenci_oceny` (
  `ID` int NOT NULL,
  `student_id` int NOT NULL,
  `ocena` float DEFAULT NULL,
  `kier_przed_prow` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `studenci_oceny`
--

INSERT INTO `studenci_oceny` (`ID`, `student_id`, `ocena`, `kier_przed_prow`) VALUES
(2, 6, 3, 17),
(3, 6, 3.5, 18),
(4, 6, NULL, 19),
(5, 6, NULL, 20),
(6, 6, NULL, 21),
(7, 6, 4.5, 22),
(8, 6, 4, 23),
(9, 6, NULL, 24),
(10, 6, NULL, 25),
(19, 3, NULL, 9),
(20, 3, NULL, 10),
(21, 3, NULL, 11),
(22, 3, NULL, 12),
(23, 4, NULL, 4),
(24, 4, NULL, 5),
(25, 4, NULL, 6),
(26, 4, NULL, 7),
(28, 4, NULL, 8),
(29, 5, NULL, 4),
(30, 5, NULL, 5),
(31, 5, NULL, 6),
(32, 5, NULL, 7),
(33, 5, NULL, 8),
(34, 7, NULL, 9),
(35, 7, NULL, 10),
(36, 7, NULL, 11),
(37, 7, NULL, 12),
(38, 8, NULL, 4),
(39, 8, NULL, 5),
(40, 8, NULL, 10),
(41, 8, NULL, 12),
(42, 8, NULL, 9),
(43, 9, NULL, 9),
(44, 9, NULL, 10),
(45, 9, NULL, 11),
(46, 9, NULL, 12),
(47, 9, NULL, 26),
(48, 9, NULL, 27),
(49, 9, 5, 28),
(50, 10, NULL, 29),
(51, 10, NULL, 30),
(52, 10, NULL, 31),
(53, 11, NULL, 1),
(54, 11, NULL, 2),
(55, 11, NULL, 3),
(56, 12, NULL, 13),
(57, 12, 4, 14),
(58, 12, NULL, 15),
(59, 12, NULL, 16),
(60, 13, NULL, 17),
(61, 13, 5, 18),
(62, 13, NULL, 19),
(63, 13, NULL, 20),
(64, 13, NULL, 21),
(65, 14, NULL, 22),
(66, 14, 3.5, 23),
(67, 14, NULL, 24),
(68, 14, NULL, 25),
(69, 15, NULL, 32),
(70, 15, NULL, 33),
(71, 15, NULL, 34),
(101, 3, NULL, 9),
(102, 3, NULL, 10),
(103, 3, NULL, 11),
(104, 3, NULL, 12),
(105, 3, NULL, 37),
(106, 3, NULL, 38),
(107, 21, NULL, 17),
(108, 21, 4, 18),
(109, 21, NULL, 19),
(110, 21, NULL, 20),
(111, 21, NULL, 21),
(112, 21, NULL, 29),
(113, 21, NULL, 30),
(114, 21, NULL, 31),
(118, 47, NULL, 29),
(119, 47, NULL, 30),
(120, 47, NULL, 31);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `wydzialy`
--

CREATE TABLE `wydzialy` (
  `ID` int NOT NULL,
  `wydzial` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL,
  `dziekan` varchar(40) CHARACTER SET utf8mb4 COLLATE utf8mb4_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_polish_ci;

--
-- Zrzut danych tabeli `wydzialy`
--

INSERT INTO `wydzialy` (`ID`, `wydzial`, `dziekan`) VALUES
(1, 'Fizyki Stosowanej', 'Jan Kot'),
(2, 'Inżynieri Metali', 'Tomasz Wróbel'),
(3, 'Informatyki', 'Michał Ptak'),
(4, 'Automatyki i Robotyki', 'Daniel Żak'),
(5, 'Geologi i Górnictwa', 'Jakub Skowron'),
(7, 'Humanistyczny', 'Barbara Lis');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `kierunki`
--
ALTER TABLE `kierunki`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `wydzial_id` (`wydzial_id`);

--
-- Indeksy dla tabeli `kierunki_przedmioty`
--
ALTER TABLE `kierunki_przedmioty`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `kierunek_id` (`kierunek_id`),
  ADD KEY `przedmiot_id` (`przedmiot_id`);

--
-- Indeksy dla tabeli `kierunki_przedmioty_prowadzacy`
--
ALTER TABLE `kierunki_przedmioty_prowadzacy`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `kierunki_przedmioty_id` (`kierunki_przedmioty_id`),
  ADD KEY `prowadzacy_id` (`prowadzacy_id`);

--
-- Indeksy dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `prowadzacy`
--
ALTER TABLE `prowadzacy`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  ADD PRIMARY KEY (`ID`);

--
-- Indeksy dla tabeli `studenci`
--
ALTER TABLE `studenci`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `nr_album` (`nr_album`),
  ADD UNIQUE KEY `telefon` (`telefon`);

--
-- Indeksy dla tabeli `studenci_kierunki`
--
ALTER TABLE `studenci_kierunki`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `studenci_kierunki_ibfk_2` (`kierunek_id`);

--
-- Indeksy dla tabeli `studenci_oceny`
--
ALTER TABLE `studenci_oceny`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `student_id` (`student_id`),
  ADD KEY `kier_przed_prow` (`kier_przed_prow`);

--
-- Indeksy dla tabeli `wydzialy`
--
ALTER TABLE `wydzialy`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `kierunki`
--
ALTER TABLE `kierunki`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT dla tabeli `kierunki_przedmioty`
--
ALTER TABLE `kierunki_przedmioty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT dla tabeli `kierunki_przedmioty_prowadzacy`
--
ALTER TABLE `kierunki_przedmioty_prowadzacy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT dla tabeli `pracownicy`
--
ALTER TABLE `pracownicy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT dla tabeli `prowadzacy`
--
ALTER TABLE `prowadzacy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT dla tabeli `przedmioty`
--
ALTER TABLE `przedmioty`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT dla tabeli `studenci`
--
ALTER TABLE `studenci`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT dla tabeli `studenci_kierunki`
--
ALTER TABLE `studenci_kierunki`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT dla tabeli `studenci_oceny`
--
ALTER TABLE `studenci_oceny`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT dla tabeli `wydzialy`
--
ALTER TABLE `wydzialy`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Ograniczenia dla zrzutów tabel
--

--
-- Ograniczenia dla tabeli `kierunki`
--
ALTER TABLE `kierunki`
  ADD CONSTRAINT `kierunki_ibfk_1` FOREIGN KEY (`wydzial_id`) REFERENCES `wydzialy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `kierunki_przedmioty`
--
ALTER TABLE `kierunki_przedmioty`
  ADD CONSTRAINT `kierunki_przedmioty_ibfk_1` FOREIGN KEY (`kierunek_id`) REFERENCES `kierunki` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kierunki_przedmioty_ibfk_2` FOREIGN KEY (`przedmiot_id`) REFERENCES `przedmioty` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `kierunki_przedmioty_prowadzacy`
--
ALTER TABLE `kierunki_przedmioty_prowadzacy`
  ADD CONSTRAINT `kierunki_przedmioty_prowadzacy_ibfk_1` FOREIGN KEY (`kierunki_przedmioty_id`) REFERENCES `kierunki_przedmioty` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kierunki_przedmioty_prowadzacy_ibfk_2` FOREIGN KEY (`prowadzacy_id`) REFERENCES `prowadzacy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `studenci_kierunki`
--
ALTER TABLE `studenci_kierunki`
  ADD CONSTRAINT `studenci_kierunki_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `studenci` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `studenci_kierunki_ibfk_2` FOREIGN KEY (`kierunek_id`) REFERENCES `kierunki` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Ograniczenia dla tabeli `studenci_oceny`
--
ALTER TABLE `studenci_oceny`
  ADD CONSTRAINT `studenci_oceny_ibfk_1` FOREIGN KEY (`kier_przed_prow`) REFERENCES `kierunki_przedmioty_prowadzacy` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
