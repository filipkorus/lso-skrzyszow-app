-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Czas generowania: 05 Wrz 2021, 00:15
-- Wersja serwera: 10.3.31-MariaDB-0ubuntu0.20.04.1
-- Wersja PHP: 8.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `lso-skrzyszow`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `ministerings`
--

CREATE TABLE `ministerings` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `day_of_week` int(11) NOT NULL,
  `hour` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `ministerings`
--

INSERT INTO `ministerings` (`id`, `uid`, `day_of_week`, `hour`) VALUES
(2, 1, 7, '11:00'),
(3, 1, 3, '18:00'),
(20, 1, 5, '6:30'),
(24, 5, 4, '18:00'),
(25, 6, 2, '6:30');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `points`
--

CREATE TABLE `points` (
  `id` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `month` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `points_plus` int(11) NOT NULL,
  `points_minus` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `points`
--

INSERT INTO `points` (`id`, `uid`, `month`, `year`, `points_plus`, `points_minus`) VALUES
(1, 1, 8, 2021, 233, 99),
(2, 1, 2, 2021, 210, 100),
(5, 5, 8, 2021, 235, 121),
(6, 6, 8, 2021, 11, 2),
(8, 1, 9, 2021, 1, 2),
(9, 4, 9, 2021, 3, 4),
(10, 5, 9, 2021, 5, 2),
(11, 6, 9, 2021, 888, 235),
(12, 1, 5, 2021, 0, 0),
(14, 4, 5, 2021, 0, 0),
(15, 5, 5, 2021, 0, 0),
(16, 6, 5, 2021, 0, 0),
(17, 4, 8, 2021, 123, 32),
(18, 1, 11, 2021, 0, 0),
(20, 4, 11, 2021, 0, 2),
(21, 5, 11, 2021, 0, 0),
(22, 6, 11, 2021, 4, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_no` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `birthdate` date NOT NULL,
  `picture` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL,
  `last_time_online` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Zrzut danych tabeli `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `phone_no`, `name`, `last_name`, `birthdate`, `picture`, `role`, `admin`, `last_time_online`) VALUES
(1, 'filek7', 'filip@gmail.com', '$2y$10$ZEM./C.PfaMjXuBpxcqF5eY7uhsjHZEtdHKtJQwNI9zsLIx.wrIbG', '672726923', 'Filip', 'Korus', '2002-12-01', '365ae9a13f86907a1f4030b1d83985266133e8f1b0895.png', 'lektor', 1, '2021-09-05 00:14:24'),
(4, 'jankowalski', 'jan@gmail.com', '$2y$10$2T7i7ezIVSdxczWdmjTQf.xdSKzrVpM7LvpcPAqcCYbKplMzJp.1W', '123456789', 'Jan', 'Kowalski', '2012-12-12', '', 'lektor', 0, '2021-09-01 14:02:13'),
(5, 'pawełnowak', 'pawelnowak@gmail.com', '$2y$10$h6ROz0MIJIdmXiBqakco3erWivz.hCmYmYNx6phNBH/SXndnQKVSm', '123456781', 'Paweł', 'Nowak', '2021-09-16', '', 'ministrant', 0, '2021-09-02 11:00:05'),
(6, 'filipnowak', 'filipnowak@gmail.com', '$2y$10$jvIf4lfFH5gRmN/zYDT7RuXv3TKnSDoZDwZ16L5AkwPd/MNb9ryom', '432434435', 'Filip', 'Nowak', '2021-09-24', '', 'ministrant', 0, '2021-09-02 11:04:02');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `ministerings`
--
ALTER TABLE `ministerings`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `points`
--
ALTER TABLE `points`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `ministerings`
--
ALTER TABLE `ministerings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT dla tabeli `points`
--
ALTER TABLE `points`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
