-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2016 at 06:11 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ayceqart`
--

-- --------------------------------------------------------

--
-- Table structure for table `ayc_admin_sidebar`
--

CREATE TABLE `ayc_admin_sidebar` (
  `id` int(10) UNSIGNED NOT NULL,
  `parent_id` int(10) UNSIGNED DEFAULT '0',
  `href` varchar(50) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_cities`
--

CREATE TABLE `ayc_cities` (
  `id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_cities`
--

INSERT INTO `ayc_cities` (`id`, `country_id`, `alias`) VALUES
(1, 1, 'ashtarak'),
(2, 1, 'yerevan');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_city_translations`
--

CREATE TABLE `ayc_city_translations` (
  `language_id` int(10) UNSIGNED NOT NULL,
  `city_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_comments`
--

CREATE TABLE `ayc_comments` (
  `id` int(10) UNSIGNED NOT NULL,
  `comment_for` enum('USER','POST','GROUP') DEFAULT 'POST',
  `item_id` int(10) UNSIGNED DEFAULT NULL,
  `from_user_id` int(10) UNSIGNED DEFAULT NULL,
  `content` varchar(250) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_connections`
--

CREATE TABLE `ayc_connections` (
  `from_id` int(10) UNSIGNED NOT NULL,
  `to_id` int(10) UNSIGNED NOT NULL,
  `verified` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `viewed` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `sent_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirm_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_connections`
--

INSERT INTO `ayc_connections` (`from_id`, `to_id`, `verified`, `viewed`, `sent_date`, `confirm_date`) VALUES
(1, 4, 0, 0, '2016-07-24 21:44:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ayc_countries`
--

CREATE TABLE `ayc_countries` (
  `id` int(10) UNSIGNED NOT NULL,
  `alias` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_countries`
--

INSERT INTO `ayc_countries` (`id`, `alias`) VALUES
(1, 'armenia'),
(2, 'russia'),
(3, 'usa');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_country_translations`
--

CREATE TABLE `ayc_country_translations` (
  `language_id` int(10) UNSIGNED NOT NULL,
  `country_id` int(10) UNSIGNED NOT NULL,
  `name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_employers`
--

CREATE TABLE `ayc_employers` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_followers`
--

CREATE TABLE `ayc_followers` (
  `from_id` int(10) UNSIGNED NOT NULL,
  `to_id` int(10) UNSIGNED NOT NULL,
  `verified` tinyint(1) UNSIGNED NOT NULL,
  `viewed` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `sent_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `confirm_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_groups`
--

CREATE TABLE `ayc_groups` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(250) DEFAULT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `active` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Ayceqart.am users groups';

-- --------------------------------------------------------

--
-- Table structure for table `ayc_languages`
--

CREATE TABLE `ayc_languages` (
  `id` int(10) UNSIGNED NOT NULL,
  `code` char(2) NOT NULL,
  `api_code` char(5) NOT NULL,
  `name` varchar(50) NOT NULL,
  `original_name` varchar(50) NOT NULL,
  `image` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_languages`
--

INSERT INTO `ayc_languages` (`id`, `code`, `api_code`, `name`, `original_name`, `image`) VALUES
(1, 'am', 'hy_AM', 'armenian', 'Հայերեն', '__VS__5753621bc394e_AYCEQART.AM_5753621bc395b.png'),
(2, 'ru', 'ru_RU', 'russian', 'Русский', '__VS__57536208c2acf_AYCEQART.AM_57536208c2ade.png'),
(3, 'en', 'en_US', 'english', 'English', '__VS__575361fb2f56b_AYCEQART.AM_575361fb2f578.png');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_messages`
--

CREATE TABLE `ayc_messages` (
  `id` int(10) UNSIGNED NOT NULL,
  `from_id` int(10) UNSIGNED NOT NULL,
  `to_id` int(10) UNSIGNED NOT NULL,
  `content` varchar(500) NOT NULL,
  `sent_time` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_posts`
--

CREATE TABLE `ayc_posts` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_post_translations`
--

CREATE TABLE `ayc_post_translations` (
  `post_id` int(10) UNSIGNED DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_professionals`
--

CREATE TABLE `ayc_professionals` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_professionals`
--

INSERT INTO `ayc_professionals` (`id`, `user_id`) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 4);

-- --------------------------------------------------------

--
-- Table structure for table `ayc_professions`
--

CREATE TABLE `ayc_professions` (
  `id` int(10) UNSIGNED NOT NULL,
  `sector_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_professions`
--

INSERT INTO `ayc_professions` (`id`, `sector_id`, `title`, `description`) VALUES
(1, 1, 'Computer Programmer', 'Computer programmers write the code that makes up software programs. These may be application programs, such as word processors or games, or they may be more sophisticated programs, such as operating systems and database management systems.'),
(2, 1, 'Network Administrator', 'Network administrators manage an organization''s network, performing typical administrative duties such as adding and deleting user IDs, as well as troubleshooting when a network problem occurs. Senior network administrators may also design and implement local area networks (LANs) and wide area networks (WANs).'),
(3, 1, 'Database Administrator', 'Database administrators manage a company’s data using database management software. Their job includes protecting the integrity of the company''s data and making sure that it is available to employees who need access to it.'),
(4, 1, 'Computer Support Specialist', 'Otherwise known as “help desk technicians,” computer support specialists help to keep personal computer users up and running. They are typically able to handle both hardware and software problems at the user level.'),
(5, 1, 'Computer Systems Analyst', 'Computer systems analysts research the needs of a company, as well as the computer systems currently in place, and recommend strategies for system upgrades, software development and other ideas for improving the operation’s efficiency.');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_profession_translations`
--

CREATE TABLE `ayc_profession_translations` (
  `prof_id` int(10) UNSIGNED NOT NULL,
  `language_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(250) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_roles`
--

CREATE TABLE `ayc_roles` (
  `name` varchar(50) DEFAULT NULL,
  `id` int(10) UNSIGNED NOT NULL,
  `active` tinyint(1) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_roles`
--

INSERT INTO `ayc_roles` (`name`, `id`, `active`) VALUES
('admin', 1, 1),
('user', 2, 1),
('employer', 3, 1),
('professional', 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ayc_sectors`
--

CREATE TABLE `ayc_sectors` (
  `id` int(10) UNSIGNED NOT NULL,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_sectors`
--

INSERT INTO `ayc_sectors` (`id`, `title`, `description`) VALUES
(1, 'Information Technologies', 'information technology is ...');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_sector_translations`
--

CREATE TABLE `ayc_sector_translations` (
  `sector_id` int(10) UNSIGNED DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_skills`
--

CREATE TABLE `ayc_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `sub_profession_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_skills`
--

INSERT INTO `ayc_skills` (`id`, `sub_profession_id`, `title`, `description`) VALUES
(1, 1, 'PHP', 'PHP is a general-purpose server-side scripting language originally designed for web development to produce dynamic web pages.'),
(2, 1, 'JavaScript', 'JavaScript is a prototype-based scripting language that is dynamic, weakly typed and has first-class functions. It is a multi-paradigm language, supporting object-oriented, imperative, and functional programming styles.'),
(3, 3, 'C++', 'C++ is a general-purpose programming language. It has imperative, object-oriented and generic programming features, while also providing facilities for low-level memory manipulation.'),
(4, 2, 'Android', 'Android is a mobile operating system currently developed by Google.'),
(5, 2, 'IOS', 'IOS is ...'),
(6, 1, 'CSS', 'Cascading Style Sheets (CSS) is a style sheet language used to describe the presentation (that is, the look and formatting) of a document written in a markup language.'),
(7, 1, 'AJAX', 'Ajax is a group of interrelated web development methods used on the client-side to create asynchronous web applications.'),
(8, 1, 'HTML', 'HTML is a language for structuring and presenting content for the World Wide Web, and is a core technology of the Internet originally proposed by Opera Software. It is the fifth revision of the HTML standard.'),
(9, 1, 'Codeigniter', 'CodeIgniter is an open source web application framework for use in building dynamic web sites with PHP'),
(10, 3, 'C#', 'C# is a multi-paradigm programming language encompassing strong typing, imperative, declarative, functional, generic, object-oriented (class-based), and component-oriented programming disciplines.'),
(11, 1, 'Laravel', 'Laravel is a free, open-source PHP web framework, created by Taylor Otwell and intended for the development of web applications following the model–view–controller (MVC) architectural pattern.'),
(12, 1, 'CakePHP', 'CakePHP is an open-source web framework. It follows the model–view–controller (MVC) approach and is written in PHP, modeled after the concepts of Ruby on Rails, and distributed under the MIT License'),
(13, 1, 'Phalcon', 'Phalcon is a high-performance PHP web framework based on the model–view–controller (MVC) pattern. Originally released in 2012, it is an open-source framework licensed under the terms of the BSD License'),
(14, 1, 'Symfony', 'Symfony is a PHP web application framework for MVC applications. Symfony is free software and released under the MIT license. The symfony-project.com website launched on October 18, 2005'),
(15, 1, 'Kohana', 'Kohana is a PHP5 HMVC framework. Kohana is licensed under the BSD license and hosted on GitHub. Issues are tracked using Redmine. It is noted for its performance when compared to CodeIgniter and other high-performance PHP frameworks'),
(16, 1, '.NET Framework', '.NET Framework is a software framework developed by Microsoft that runs primarily on Microsoft Windows. It includes a large class library known as Framework Class Library (FCL) and provides language interoperability (each language can use code written in other languages) across several programming languages'),
(32, 2, 'AngularJS', 'AngularJS is...'),
(33, 3, 'AngularJS', 'AngularJS');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_skill_translations`
--

CREATE TABLE `ayc_skill_translations` (
  `skill_id` int(10) UNSIGNED DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_sub_professions`
--

CREATE TABLE `ayc_sub_professions` (
  `id` int(10) UNSIGNED NOT NULL,
  `profession_id` int(10) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_sub_professions`
--

INSERT INTO `ayc_sub_professions` (`id`, `profession_id`, `title`, `description`) VALUES
(1, 1, 'Web Developer', 'A web developer is a programmer who specializes in, or is specifically engaged in, the development of World Wide Web applications.'),
(2, 1, 'Mobile Developer', 'Mobile development is'),
(3, 1, 'Windows Developer', 'Windows development is');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_sub_profession_translations`
--

CREATE TABLE `ayc_sub_profession_translations` (
  `sub_profession_id` int(10) UNSIGNED DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(250) DEFAULT NULL,
  `description` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `ayc_users`
--

CREATE TABLE `ayc_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  `unique_id` varchar(70) NOT NULL,
  `email` varchar(254) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `password` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `language_id` int(11) UNSIGNED NOT NULL DEFAULT '1',
  `terms` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `verified` tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
  `logged_first_time` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `deleted` tinyint(1) UNSIGNED DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_users`
--

INSERT INTO `ayc_users` (`id`, `role_id`, `unique_id`, `email`, `phone`, `password`, `username`, `image`, `language_id`, `terms`, `verified`, `logged_first_time`, `deleted`, `created_at`, `last_login`) VALUES
(1, 4, 'var.stepanyan.varazdat', 'var-stepanyan@mail.ru', '+ ( 374 ) 98 71-72-07', '$2y$11$71dac5db8c490e24de353uIItTRtBo5jALtjtS9L.xQ9rUnV8FXUC', 'Varazdat', '7PjFodd8U_VarYan`s_WwGRPcTZf.jpg', 3, 1, 0, 1, 0, '2016-02-17 00:00:00', 1456666860),
(2, 4, '077502021.davyan', '077502021@mail.ru', NULL, '$2y$11$71dac5db8c490e24de353uIItTRtBo5jALtjtS9L.xQ9rUnV8FXUC', 'davyan', 'jt0Kmx06Q_VarYan`s_eUS50PiXR.jpg', 1, 1, 0, 1, 0, '2016-02-15 00:00:00', NULL),
(3, 4, 'arman.karapetyan.arman1991', 'arman.karapetyan@haypost.am', NULL, '$2y$11$71dac5db8c490e24de353uIItTRtBo5jALtjtS9L.xQ9rUnV8FXUC', 'arman1991', '57urgHWFf_VarYan`s_WSLfGfkYe.jpg', 1, 1, 0, 1, 0, '2016-02-04 00:00:00', NULL),
(4, 4, 'naida181.anya.anahit', 'naida181@mail.ru', NULL, '$2y$11$71dac5db8c490e24de353uIItTRtBo5jALtjtS9L.xQ9rUnV8FXUC', 'anya.anahit', 'OABn3ftfz_VarYan`s_56c246a9d7ea1.jpg', 1, 1, 0, 1, 0, '2016-02-02 00:00:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ayc_user_attachment`
--

CREATE TABLE `ayc_user_attachment` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `image_name` varchar(255) NOT NULL,
  `type` enum('IMAGE','FILE','AUDIO','VIDEO') DEFAULT 'IMAGE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_user_attachment`
--

INSERT INTO `ayc_user_attachment` (`id`, `user_id`, `image_name`, `type`) VALUES
(3, 1, 'RTDz2NXUa_AYCEQART.AM_56c623397e7de.jpg', 'IMAGE'),
(6, 1, 'rEUMKp42X_AYCEQART.AM_56c6233a44659.jpg', 'IMAGE');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_user_configurations`
--

CREATE TABLE `ayc_user_configurations` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `auto_connect` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `auto_follow` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `confirm_delete` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `confirm_logout` tinyint(1) UNSIGNED NOT NULL DEFAULT '1',
  `auto_lock` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_user_configurations`
--

INSERT INTO `ayc_user_configurations` (`user_id`, `auto_connect`, `auto_follow`, `confirm_delete`, `confirm_logout`, `auto_lock`) VALUES
(1, 0, 0, 1, 1, 1),
(2, 0, 0, 1, 1, 1),
(3, 0, 0, 1, 1, 1),
(4, 0, 0, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ayc_user_groups`
--

CREATE TABLE `ayc_user_groups` (
  `user_id` int(10) UNSIGNED NOT NULL,
  `group_id` int(10) UNSIGNED NOT NULL,
  `position` enum('MASTER','MEMBER') DEFAULT 'MEMBER'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Relation for users and groups';

-- --------------------------------------------------------

--
-- Table structure for table `ayc_user_info`
--

CREATE TABLE `ayc_user_info` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `gender` enum('male','female') NOT NULL DEFAULT 'male',
  `birthday` date NOT NULL,
  `street_name` varchar(50) DEFAULT NULL,
  `street_number` varchar(25) DEFAULT NULL,
  `country_id` int(10) UNSIGNED DEFAULT NULL,
  `city_id` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_user_info`
--

INSERT INTO `ayc_user_info` (`id`, `user_id`, `gender`, `birthday`, `street_name`, `street_number`, `country_id`, `city_id`) VALUES
(1, 1, 'male', '1991-02-08', 'Raffi', '9', 1, 1),
(2, 2, 'male', '1900-12-07', 'no street', '42', 1, 1),
(3, 3, 'male', '1900-12-07', 'editable', '12', 1, 2),
(4, 4, 'female', '1990-12-18', 'Raffi', '9', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ayc_user_skills`
--

CREATE TABLE `ayc_user_skills` (
  `id` int(10) UNSIGNED NOT NULL,
  `user_id` int(10) UNSIGNED NOT NULL,
  `skill_id` int(10) UNSIGNED NOT NULL,
  `experience` tinyint(2) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_user_skills`
--

INSERT INTO `ayc_user_skills` (`id`, `user_id`, `skill_id`, `experience`) VALUES
(1, 1, 1, 4),
(2, 1, 2, 2),
(3, 1, 4, 2),
(4, 1, 5, 2),
(5, 1, 6, 2),
(6, 1, 7, 2),
(7, 1, 8, 2),
(8, 1, 9, 2),
(9, 2, 1, 1),
(10, 2, 2, 1),
(11, 2, 6, 1),
(12, 2, 8, 1),
(13, 2, 9, 1),
(14, 3, 1, 4),
(15, 3, 2, 4),
(16, 3, 6, 4),
(17, 3, 7, 4),
(18, 3, 8, 4),
(19, 1, 10, 2),
(20, 4, 8, 1),
(21, 4, 8, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ayc_user_translations`
--

CREATE TABLE `ayc_user_translations` (
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `language_id` int(10) UNSIGNED DEFAULT NULL,
  `first_name` varchar(250) DEFAULT NULL,
  `last_name` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='users information translation';

--
-- Dumping data for table `ayc_user_translations`
--

INSERT INTO `ayc_user_translations` (`user_id`, `language_id`, `first_name`, `last_name`) VALUES
(1, 1, 'Վարազդատ', 'Ստեփանյան'),
(2, 1, 'Դավիթ', 'Խաչատրյան'),
(3, 1, 'Արման', 'Կարապետյան'),
(4, 1, 'Անահիտ', 'Ստեփանյան'),
(1, 2, 'Вараздат', 'Степанян'),
(2, 2, 'Давид', 'Хачатрян'),
(3, 2, 'Арман', 'Карапетян'),
(4, 2, 'Анаит', 'Степанян'),
(1, 3, 'Varazdat', 'Stepanyan'),
(2, 3, 'Davit', 'Khachatryan');

-- --------------------------------------------------------

--
-- Table structure for table `ayc_visitors`
--

CREATE TABLE `ayc_visitors` (
  `from_id` int(10) UNSIGNED NOT NULL,
  `to_id` int(10) UNSIGNED NOT NULL,
  `visit_date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `ayc_visitors`
--

INSERT INTO `ayc_visitors` (`from_id`, `to_id`, `visit_date`) VALUES
(1, 2, '2016-02-28 14:36:33'),
(1, 3, '2016-03-14 14:36:33');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ayc_admin_sidebar`
--
ALTER TABLE `ayc_admin_sidebar`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ayc_admin_sidebar_href_uindex` (`href`),
  ADD KEY `ayc_sidebar_ayc_sidebar_id_fk` (`parent_id`);

--
-- Indexes for table `ayc_cities`
--
ALTER TABLE `ayc_cities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_cities_ayc_countries_id_fk` (`country_id`);

--
-- Indexes for table `ayc_city_translations`
--
ALTER TABLE `ayc_city_translations`
  ADD KEY `ayc_city_translations_ayc_languages_id_fk` (`language_id`),
  ADD KEY `ayc_city_translations_ayc_cities_id_fk` (`city_id`);

--
-- Indexes for table `ayc_comments`
--
ALTER TABLE `ayc_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_comments_ayc_users_id_fk` (`from_user_id`);

--
-- Indexes for table `ayc_connections`
--
ALTER TABLE `ayc_connections`
  ADD KEY `ayc_connections_ayc_users_from_id_fk` (`from_id`),
  ADD KEY `ayc_connections_ayc_users_to_id_fk` (`to_id`);

--
-- Indexes for table `ayc_countries`
--
ALTER TABLE `ayc_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ayc_country_translations`
--
ALTER TABLE `ayc_country_translations`
  ADD KEY `ayc_country_translations_ayc_languages_id_fk` (`language_id`),
  ADD KEY `ayc_country_translations_ayc_countries_id_fk` (`country_id`);

--
-- Indexes for table `ayc_employers`
--
ALTER TABLE `ayc_employers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_employers_ayc_users_id_fk` (`user_id`);

--
-- Indexes for table `ayc_followers`
--
ALTER TABLE `ayc_followers`
  ADD KEY `ayc_followers_ayc_users_from_id_fk` (`from_id`),
  ADD KEY `ayc_followers_ayc_users_to_id_fk` (`to_id`);

--
-- Indexes for table `ayc_groups`
--
ALTER TABLE `ayc_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ayc_groups_name_uindex` (`name`),
  ADD KEY `ayc_groups_ayc_users_id_fk` (`user_id`);

--
-- Indexes for table `ayc_languages`
--
ALTER TABLE `ayc_languages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ayc_messages`
--
ALTER TABLE `ayc_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_messages_ayc_users_id_fk` (`from_id`),
  ADD KEY `ayc_messages_ayc_users_to_id_fk` (`to_id`);

--
-- Indexes for table `ayc_posts`
--
ALTER TABLE `ayc_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_posts_ayc_users_id_fk` (`user_id`);

--
-- Indexes for table `ayc_post_translations`
--
ALTER TABLE `ayc_post_translations`
  ADD KEY `ayc_post_translations_ayc_posts_id_fk` (`post_id`),
  ADD KEY `ayc_post_translations_ayc_languages_id_fk` (`language_id`);

--
-- Indexes for table `ayc_professionals`
--
ALTER TABLE `ayc_professionals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_professionals_ayc_users_id_fk` (`user_id`);

--
-- Indexes for table `ayc_professions`
--
ALTER TABLE `ayc_professions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_professions_ayc_sectors_id_fk` (`sector_id`);

--
-- Indexes for table `ayc_profession_translations`
--
ALTER TABLE `ayc_profession_translations`
  ADD KEY `ayc_profession_translations_ayc_professions_id_fk` (`prof_id`),
  ADD KEY `ayc_profession_translations_ayc_languages_id_fk` (`language_id`);

--
-- Indexes for table `ayc_roles`
--
ALTER TABLE `ayc_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ayc_sectors`
--
ALTER TABLE `ayc_sectors`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ayc_sector_translations`
--
ALTER TABLE `ayc_sector_translations`
  ADD KEY `ayc_sector_translations_ayc_sectors_id_fk` (`sector_id`),
  ADD KEY `ayc_sector_translations_ayc_languages_id_fk` (`language_id`);

--
-- Indexes for table `ayc_skills`
--
ALTER TABLE `ayc_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_skills_ayc_sub_professions_id_fk` (`sub_profession_id`);

--
-- Indexes for table `ayc_skill_translations`
--
ALTER TABLE `ayc_skill_translations`
  ADD KEY `ayc_skill_translations_ayc_skills_id_fk` (`skill_id`),
  ADD KEY `ayc_skill_translations_ayc_languages_id_fk` (`language_id`);

--
-- Indexes for table `ayc_sub_professions`
--
ALTER TABLE `ayc_sub_professions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_sub_professions_ayc_professions_id_fk` (`profession_id`);

--
-- Indexes for table `ayc_sub_profession_translations`
--
ALTER TABLE `ayc_sub_profession_translations`
  ADD KEY `ayc_sub_profession_translations_ayc_sub_professions_id_fk` (`sub_profession_id`),
  ADD KEY `ayc_sub_profession_translations_ayc_languages_id_fk` (`language_id`);

--
-- Indexes for table `ayc_users`
--
ALTER TABLE `ayc_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `ayc_users_username_uindex` (`username`),
  ADD KEY `ayc_users_ayc_languages_id_fk` (`language_id`),
  ADD KEY `ayc_users_ayc_roles_id_fk` (`role_id`);

--
-- Indexes for table `ayc_user_attachment`
--
ALTER TABLE `ayc_user_attachment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_user_images_ayc_users_id_fk` (`user_id`);

--
-- Indexes for table `ayc_user_configurations`
--
ALTER TABLE `ayc_user_configurations`
  ADD KEY `ayc_user_configurations_ayc_users_id_fk` (`user_id`);

--
-- Indexes for table `ayc_user_groups`
--
ALTER TABLE `ayc_user_groups`
  ADD KEY `ayc_user_groups_ayc_users_id_fk` (`user_id`),
  ADD KEY `ayc_user_groups_ayc_groups_id_fk` (`group_id`);

--
-- Indexes for table `ayc_user_info`
--
ALTER TABLE `ayc_user_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_user_info_ayc_users_id_fk` (`user_id`),
  ADD KEY `ayc_user_info_ayc_countries_id_fk` (`country_id`),
  ADD KEY `ayc_user_info_ayc_cities_id_fk` (`city_id`);

--
-- Indexes for table `ayc_user_skills`
--
ALTER TABLE `ayc_user_skills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ayc_user_skills_ayc_users_id_fk` (`user_id`),
  ADD KEY `ayc_user_skills_ayc_skills_id_fk` (`skill_id`);

--
-- Indexes for table `ayc_user_translations`
--
ALTER TABLE `ayc_user_translations`
  ADD KEY `ayc_user_translations_ayc_users_id_fk` (`user_id`),
  ADD KEY `ayc_user_translations_ayc_languages_id_fk` (`language_id`);
ALTER TABLE `ayc_user_translations` ADD FULLTEXT KEY `first_name` (`first_name`,`last_name`);

--
-- Indexes for table `ayc_visitors`
--
ALTER TABLE `ayc_visitors`
  ADD KEY `ayc_visitors_ayc_users_from_id_fk` (`from_id`),
  ADD KEY `ayc_visitors_ayc_users_to_id_fk` (`to_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ayc_admin_sidebar`
--
ALTER TABLE `ayc_admin_sidebar`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ayc_cities`
--
ALTER TABLE `ayc_cities`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `ayc_comments`
--
ALTER TABLE `ayc_comments`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ayc_countries`
--
ALTER TABLE `ayc_countries`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ayc_employers`
--
ALTER TABLE `ayc_employers`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ayc_groups`
--
ALTER TABLE `ayc_groups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ayc_languages`
--
ALTER TABLE `ayc_languages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ayc_messages`
--
ALTER TABLE `ayc_messages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ayc_posts`
--
ALTER TABLE `ayc_posts`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `ayc_professionals`
--
ALTER TABLE `ayc_professionals`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ayc_professions`
--
ALTER TABLE `ayc_professions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `ayc_roles`
--
ALTER TABLE `ayc_roles`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ayc_sectors`
--
ALTER TABLE `ayc_sectors`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `ayc_skills`
--
ALTER TABLE `ayc_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT for table `ayc_sub_professions`
--
ALTER TABLE `ayc_sub_professions`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `ayc_users`
--
ALTER TABLE `ayc_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ayc_user_attachment`
--
ALTER TABLE `ayc_user_attachment`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `ayc_user_info`
--
ALTER TABLE `ayc_user_info`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `ayc_user_skills`
--
ALTER TABLE `ayc_user_skills`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `ayc_admin_sidebar`
--
ALTER TABLE `ayc_admin_sidebar`
  ADD CONSTRAINT `ayc_sidebar_ayc_sidebar_id_fk` FOREIGN KEY (`parent_id`) REFERENCES `ayc_admin_sidebar` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_cities`
--
ALTER TABLE `ayc_cities`
  ADD CONSTRAINT `ayc_cities_ayc_countries_id_fk` FOREIGN KEY (`country_id`) REFERENCES `ayc_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_city_translations`
--
ALTER TABLE `ayc_city_translations`
  ADD CONSTRAINT `ayc_city_translations_ayc_cities_id_fk` FOREIGN KEY (`city_id`) REFERENCES `ayc_cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_city_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ayc_comments`
--
ALTER TABLE `ayc_comments`
  ADD CONSTRAINT `ayc_comments_ayc_users_id_fk` FOREIGN KEY (`from_user_id`) REFERENCES `ayc_users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ayc_connections`
--
ALTER TABLE `ayc_connections`
  ADD CONSTRAINT `ayc_connections_ayc_users_from_id_fk` FOREIGN KEY (`from_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_connections_ayc_users_to_id_fk` FOREIGN KEY (`to_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_country_translations`
--
ALTER TABLE `ayc_country_translations`
  ADD CONSTRAINT `ayc_country_translations_ayc_countries_id_fk` FOREIGN KEY (`country_id`) REFERENCES `ayc_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_country_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ayc_employers`
--
ALTER TABLE `ayc_employers`
  ADD CONSTRAINT `ayc_employers_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_followers`
--
ALTER TABLE `ayc_followers`
  ADD CONSTRAINT `ayc_followers_ayc_users_from_id_fk` FOREIGN KEY (`from_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_followers_ayc_users_to_id_fk` FOREIGN KEY (`to_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_groups`
--
ALTER TABLE `ayc_groups`
  ADD CONSTRAINT `ayc_groups_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_messages`
--
ALTER TABLE `ayc_messages`
  ADD CONSTRAINT `ayc_messages_ayc_users_id_fk` FOREIGN KEY (`from_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_messages_ayc_users_to_id_fk` FOREIGN KEY (`to_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_posts`
--
ALTER TABLE `ayc_posts`
  ADD CONSTRAINT `ayc_posts_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_post_translations`
--
ALTER TABLE `ayc_post_translations`
  ADD CONSTRAINT `ayc_post_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_post_translations_ayc_posts_id_fk` FOREIGN KEY (`post_id`) REFERENCES `ayc_posts` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_professionals`
--
ALTER TABLE `ayc_professionals`
  ADD CONSTRAINT `ayc_professionals_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_professions`
--
ALTER TABLE `ayc_professions`
  ADD CONSTRAINT `ayc_professions_ayc_sectors_id_fk` FOREIGN KEY (`sector_id`) REFERENCES `ayc_sectors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_profession_translations`
--
ALTER TABLE `ayc_profession_translations`
  ADD CONSTRAINT `ayc_profession_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_profession_translations_ayc_professions_id_fk` FOREIGN KEY (`prof_id`) REFERENCES `ayc_professions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_sector_translations`
--
ALTER TABLE `ayc_sector_translations`
  ADD CONSTRAINT `ayc_sector_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_sector_translations_ayc_sectors_id_fk` FOREIGN KEY (`sector_id`) REFERENCES `ayc_sectors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_skills`
--
ALTER TABLE `ayc_skills`
  ADD CONSTRAINT `ayc_skills_ayc_sub_professions_id_fk` FOREIGN KEY (`sub_profession_id`) REFERENCES `ayc_sub_professions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_skill_translations`
--
ALTER TABLE `ayc_skill_translations`
  ADD CONSTRAINT `ayc_skill_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_skill_translations_ayc_skills_id_fk` FOREIGN KEY (`skill_id`) REFERENCES `ayc_skills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_sub_professions`
--
ALTER TABLE `ayc_sub_professions`
  ADD CONSTRAINT `ayc_sub_professions_ayc_professions_id_fk` FOREIGN KEY (`profession_id`) REFERENCES `ayc_professions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_sub_profession_translations`
--
ALTER TABLE `ayc_sub_profession_translations`
  ADD CONSTRAINT `ayc_sub_profession_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_sub_profession_translations_ayc_sub_professions_id_fk` FOREIGN KEY (`sub_profession_id`) REFERENCES `ayc_sub_professions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_users`
--
ALTER TABLE `ayc_users`
  ADD CONSTRAINT `ayc_users_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`),
  ADD CONSTRAINT `ayc_users_ayc_roles_id_fk` FOREIGN KEY (`role_id`) REFERENCES `ayc_roles` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ayc_user_attachment`
--
ALTER TABLE `ayc_user_attachment`
  ADD CONSTRAINT `ayc_user_images_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_user_configurations`
--
ALTER TABLE `ayc_user_configurations`
  ADD CONSTRAINT `ayc_user_configurations_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_user_groups`
--
ALTER TABLE `ayc_user_groups`
  ADD CONSTRAINT `ayc_user_groups_ayc_groups_id_fk` FOREIGN KEY (`group_id`) REFERENCES `ayc_groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_user_groups_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `ayc_user_info`
--
ALTER TABLE `ayc_user_info`
  ADD CONSTRAINT `ayc_user_info_ayc_cities_id_fk` FOREIGN KEY (`city_id`) REFERENCES `ayc_cities` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_user_info_ayc_countries_id_fk` FOREIGN KEY (`country_id`) REFERENCES `ayc_countries` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_user_info_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_user_skills`
--
ALTER TABLE `ayc_user_skills`
  ADD CONSTRAINT `ayc_user_skills_ayc_skills_id_fk` FOREIGN KEY (`skill_id`) REFERENCES `ayc_skills` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_user_skills_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_user_translations`
--
ALTER TABLE `ayc_user_translations`
  ADD CONSTRAINT `ayc_user_translations_ayc_languages_id_fk` FOREIGN KEY (`language_id`) REFERENCES `ayc_languages` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_user_translations_ayc_users_id_fk` FOREIGN KEY (`user_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ayc_visitors`
--
ALTER TABLE `ayc_visitors`
  ADD CONSTRAINT `ayc_visitors_ayc_users_from_id_fk` FOREIGN KEY (`from_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ayc_visitors_ayc_users_to_id_fk` FOREIGN KEY (`to_id`) REFERENCES `ayc_users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
