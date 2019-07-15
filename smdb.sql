-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- 생성 시간: 19-01-23 10:01
-- 서버 버전: 10.1.35-MariaDB
-- PHP 버전: 7.2.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE DATABASE `smdb`;
USE `smdb`;

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 데이터베이스: `smdb`
--

-- --------------------------------------------------------

--
-- 테이블 구조 `tbl_data`
--

CREATE TABLE `tbl_data` (
  `table_idx` int(11) NOT NULL,
  `data_idx` int(11) NOT NULL,
  `data_name` varchar(255) NOT NULL,
  `data_date` date DEFAULT NULL,
  `data_vector_type` varchar(255) DEFAULT NULL,
  `data_concentration` varchar(255) DEFAULT NULL,
  `data_etc` varchar(255) DEFAULT NULL,
  `data_row` int(11) NOT NULL,
  `data_col` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `tbl_table`
--

CREATE TABLE `tbl_table` (
  `table_idx` int(11) NOT NULL,
  `table_name` varchar(255) NOT NULL,
  `table_disc` varchar(255) NOT NULL,
  `id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `tbl_user`
--

CREATE TABLE `tbl_user` (
  `user_id` varchar(255) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `user_name` varchar(50) NOT NULL,
  `user_team` varchar(255) NOT NULL,
  `info_agree_date` datetime NOT NULL,
  `term_agree_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- 테이블 구조 `user_setting`
--

CREATE TABLE `user_setting` (
  `user_id` varchar(255) DEFAULT NULL,
  `language` int(11) NOT NULL DEFAULT '0',
  `theme` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- 덤프된 테이블의 인덱스
--

--
-- 테이블의 인덱스 `tbl_data`
--
ALTER TABLE `tbl_data`
  ADD PRIMARY KEY (`data_idx`),
  ADD KEY `table_idx` (`table_idx`);

--
-- 테이블의 인덱스 `tbl_table`
--
ALTER TABLE `tbl_table`
  ADD PRIMARY KEY (`table_idx`),
  ADD KEY `id` (`id`);

--
-- 테이블의 인덱스 `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`user_id`);

--
-- 테이블의 인덱스 `user_setting`
--
ALTER TABLE `user_setting`
  ADD KEY `user_id` (`user_id`);

--
-- 덤프된 테이블의 AUTO_INCREMENT
--

--
-- 테이블의 AUTO_INCREMENT `tbl_data`
--
ALTER TABLE `tbl_data`
  MODIFY `data_idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- 테이블의 AUTO_INCREMENT `tbl_table`
--
ALTER TABLE `tbl_table`
  MODIFY `table_idx` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

--
-- 덤프된 테이블의 제약사항
--

--
-- 테이블의 제약사항 `tbl_data`
--
ALTER TABLE `tbl_data`
  ADD CONSTRAINT `tbl_data_ibfk_1` FOREIGN KEY (`table_idx`) REFERENCES `tbl_table` (`table_idx`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `tbl_table`
--
ALTER TABLE `tbl_table`
  ADD CONSTRAINT `tbl_table_ibfk_1` FOREIGN KEY (`id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE;

--
-- 테이블의 제약사항 `user_setting`
--
ALTER TABLE `user_setting`
  ADD CONSTRAINT `user_setting_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
