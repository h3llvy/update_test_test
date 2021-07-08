-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Время создания: Июл 08 2021 г., 05:59
-- Версия сервера: 8.0.13-4
-- Версия PHP: 7.2.24-0ubuntu0.18.04.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `RmIITGExF2`
--

-- --------------------------------------------------------

--
-- Структура таблицы `user`
--

CREATE TABLE `user` (
  `key` int(10) UNSIGNED NOT NULL,
  `id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(74) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `code` mediumint(6) DEFAULT NULL,
  `time_end` bigint(10) DEFAULT NULL,
  `pass` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(256) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT 'https://h3llvy.000webhostapp.com/src/ava_def.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Дамп данных таблицы `user`
--

INSERT INTO `user` (`key`, `id`, `name`, `email`, `state`, `code`, `time_end`, `pass`, `photo`) VALUES
(1, 'eaf4a2ae40d5d28d8a3818703da67157', 'Обычный ползователь', 'civat74971@advew.com', 1, 574119, 1625407383, '25d55ad283aa400af464c76d713c07ad', 'https://i.pinimg.com/originals/49/11/a9/4911a934710eede408be06cef5434004.png'),
(2, '2', 'Дима', 'ivan@mail.ru', 0, 123456, 20210703004006, '827ccb0eea8a706c4c34a16891f84e7b', 'https://androidprog.com/wp-content/uploads/2020/05/cartooncraft.jpg'),
(3, '0123', 'Maxim', 'makss.web@gmail.com', 1, 0, 0, '67c1cbb549407d754fe1894f74b40c3e', 'https://chocolatier.ru/images/users/avatars/76e8a0f1477db12933b1ca60381c873c.jpg'),
(4, '5619dec76c72a734f613780c04690dc7', 'ADMIN', 'admin', 1, 929221, 1625324800, '21232f297a57a5a743894a0e4a801fc3', 'https://i.ibb.co/VxQQmbj/kisspng-computer-icons-icon-design-business-administration-admin-icon-5b46fc46cb14d0-317019951531378.png'),
(5, '67f40dbea218c037b9aa52425a254319', 'Наруто', 'sanakoh144@noobf.com', 1, 312729, 1625312151, 'c4ca4238a0b923820dcc509a6f75849b', 'https://i.pinimg.com/736x/fe/af/c4/feafc4441c41294d5922590a49afea41.jpg'),
(6, '58adb84e58ca84eaf009149db0ccc012', NULL, 'nifyahurdo@biyac.com', 0, NULL, NULL, 'dd4b21e9ef71e1291183a46b913ae6f2', 'https://h3llvy.000webhostapp.com/src/ava_def.png'),
(7, '0eb7eb37cecec9d1d18f8e6c94ab8415', NULL, 'Whois@mail.ru', 0, NULL, NULL, '25d55ad283aa400af464c76d713c07ad', 'https://h3llvy.000webhostapp.com/src/ava_def.png'),
(8, '56b70e36e70e5a0823238038295b558b', NULL, 'ASD@MAIL.RU', 0, NULL, NULL, 'cfe95b64ac715d64275365ede690ee7c', 'https://h3llvy.000webhostapp.com/src/ava_def.png'),
(9, '8d3c5765a457acd2500c80a2994a3acf', 'Хлопчык', 'TEST@TEST.TEST', 0, 0, 0, '67c1cbb549407d754fe1894f74b40c3e', ''),
(10, 'd54122d8c1f760280977b6b7bb20f42d', NULL, 'test@test.testt', 0, NULL, NULL, '05a671c66aefea124cc08b76ea6d30bb', 'https://h3llvy.000webhostapp.com/src/ava_def.png'),
(11, '2e504c3552c1b0b0f7c51b7ab2628055', NULL, 'talanov@mail.ru', 0, 926346, 1625431451, 'a0850db8f48ebbee30c1e31949386c17', 'https://h3llvy.000webhostapp.com/src/ava_def.png'),
(72, '007c2917e82341b15c8e693f237d214f', '-', 'zagnijemlu@biyac.com', 0, 658585, 1625697800, '6144c81dea92f29d7a46c654a8294ebd', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTv3qqRlHAoe5yTI7qTfZrCEr4SurL9lUIqNQ&usqp=CAU'),
(73, '2776a77ef66ef1a1120dca72c174d4db', 'Пользователь', 'wyr83ijw5i@buy-blog.com', 0, 287935, 1625719661, '352929049dd9fad32b0d275f5ed3165c', 'https://mastiffmaster.com/wp-content/uploads/2018/05/angry-dog-square-1024x1024.jpg');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`key`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `user`
--
ALTER TABLE `user`
  MODIFY `key` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
