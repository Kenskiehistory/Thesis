-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 23, 2024 at 01:40 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ace_review`
--

-- --------------------------------------------------------

--
-- Table structure for table `announcements`
--

CREATE TABLE `announcements` (
  `id` int(11) NOT NULL,
  `courseName` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_by` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `announcements`
--

INSERT INTO `announcements` (`id`, `courseName`, `title`, `content`, `created_by`, `created_at`, `section_id`) VALUES
(8, 'Architecture', 'coruseName', 'CourseName', 'lester cal', '2024-06-22 13:55:32', 3),
(9, 'Architecture', 'anouncement debug', 'hello this is a annoucement debug', 'Admin', '2024-06-22 14:03:46', NULL),
(10, 'Civil Engineering', 'test', 'subject testing', 'civil lstname', '2024-07-03 12:51:53', 1);

-- --------------------------------------------------------

--
-- Table structure for table `answer_key`
--

CREATE TABLE `answer_key` (
  `id` int(11) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `answers` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `answer_key`
--

INSERT INTO `answer_key` (`id`, `course_name`, `answers`, `created_at`) VALUES
(1, 'Architecture', 'A,B,C,D,A,B,C,D,A,B,C,D,A,B,C,D,A,B,C,D,A,B,C,D,A', '2024-09-23 04:56:27'),
(2, 'Civil Engineering', 'B,A,D,C,B,A,D,C,B,A,D,C,B,A,D,C,B,A,D,C,B,A,D,C,B', '2024-09-23 04:56:27'),
(3, 'Mechanical Engineering', 'C,C,C,C,C,D,D,D,D,D,A,A,A,A,A,B,B,B,B,B,E,E,E,E,E', '2024-09-23 04:56:27');

-- --------------------------------------------------------

--
-- Table structure for table `arki_stud`
--

CREATE TABLE `arki_stud` (
  `student_id` int(11) NOT NULL,
  `courseReview` varchar(255) DEFAULT NULL,
  `courseSection` varchar(255) DEFAULT NULL,
  `courseStatus` varchar(255) DEFAULT NULL,
  `school_grad` varchar(255) DEFAULT NULL,
  `graduated_arki` varchar(255) DEFAULT NULL,
  `employ_status_arki` varchar(255) DEFAULT NULL,
  `additional_info_arki` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `arki_stud`
--

INSERT INTO `arki_stud` (`student_id`, `courseReview`, `courseSection`, `courseStatus`, `school_grad`, `graduated_arki`, `employ_status_arki`, `additional_info_arki`) VALUES
(83, 'Full-Course | ALE BLENDED F2F  FULL REVIEW TOTAL FEE PHP.6000', 'EVENING | 6:00 Pm - 9:00 PM', 'Returning enrollee', 'DWSSII', NULL, 'Employed', 'secret'),
(85, 'Full-Course | ALE BLENDED F2F  FULL REVIEW TOTAL FEE PHP.6000', 'EVENING | 6:00 Pm - 9:00 PM', 'New Enrollee', 'pnchs', NULL, 'Unemployed', 'none'),
(86, 'Full-Course | ALE BLENDED F2F  FULL REVIEW TOTAL FEE PHP.6000', 'EVENING | 6:00 Pm - 9:00 PM', 'Returning enrollee', 'DWSSII', NULL, 'Employed', 'secret'),
(89, 'Full-Course | ALE BLENDED F2F  FULL REVIEW TOTAL FEE PHP.6000', 'EVENING | 6:00 Pm - 9:00 PM', 'Returning enrollee', 'DWSSII', NULL, 'Employed', 'hehe'),
(90, 'Full-Course | ALE BLENDED F2F  FULL REVIEW TOTAL FEE PHP.6000', 'EVENING | 6:00 Pm - 9:00 PM', 'New Enrollee', 'Dwssii', NULL, 'Employed', 'something'),
(91, 'Full-Course | ALE BLENDED F2F  FULL REVIEW TOTAL FEE PHP.6000', 'EVENING | 6:00 Pm - 9:00 PM', 'Currently enrolled in Other Online Review Center', 'Cpu', NULL, 'Business Owner', 'Mamamo');

-- --------------------------------------------------------

--
-- Table structure for table `civil_stud`
--

CREATE TABLE `civil_stud` (
  `student_id` int(11) NOT NULL,
  `courseReview` varchar(255) DEFAULT NULL,
  `courseSection` varchar(255) DEFAULT NULL,
  `courseStatus` varchar(255) DEFAULT NULL,
  `school_grad` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `civil_stud`
--

INSERT INTO `civil_stud` (`student_id`, `courseReview`, `courseSection`, `courseStatus`, `school_grad`) VALUES
(82, 'CE REFRESHER (MARCH 2024 - APRIL 2024) | F2F TOTAL FEE PHP.15000', 'MORNING | 8:30 AM-12:00 NN', 'New Taker / New Enrollee', 'DWSSII'),
(84, 'CE REFRESHER (MARCH 2024 - APRIL 2024) | F2F TOTAL FEE PHP.15000', 'MORNING | 8:30 AM-12:00 NN', 'Retaker from other Review Center (Please provide Notice Of Admission or any proof of taking the Board Exam to our facebook Accoun)', 'DWSSII'),
(87, 'CE REFRESHER (MARCH 2024 - APRIL 2024) | F2F TOTAL FEE PHP.15000', 'MORNING | 8:30 AM-12:00 NN', 'New Taker / New Enrollee', 'DWSSII'),
(88, 'CE REFRESHER (MARCH 2024 - APRIL 2024) | F2F TOTAL FEE PHP.15000', 'MORNING | 8:30 AM-12:00 NN', 'New Taker / New Enrollee', 'heh');

-- --------------------------------------------------------

--
-- Table structure for table `course_reviews`
--

CREATE TABLE `course_reviews` (
  `id` int(11) NOT NULL,
  `coursename` text NOT NULL,
  `reviews` text NOT NULL,
  `tuition_fee` float NOT NULL,
  `review_status` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `course_reviews`
--

INSERT INTO `course_reviews` (`id`, `coursename`, `reviews`, `tuition_fee`, `review_status`) VALUES
(1, 'Civil Engineering', 'CE REFRESHER (MARCH 2024 - APRIL 2024) | F2F', 15000, 'Active'),
(2, 'Civil Engineering', 'CE REFRESHER (MARCH 2024 - APRIL 2024) | ONLINE', 150000, 'Active'),
(3, 'Architecture', 'Full-Course | ALE BLENDED F2F  FULL REVIEW', 6000, 'Active'),
(4, 'Architecture', 'Full-Course | ALE ONLINE  FULL REVIEW', 6000, 'Active'),
(5, 'Architecture', 'DESIGN AND REFRESHER | ALE BLENDED F2F  FULL REVIEW', 6000, 'Active'),
(6, 'Architecture', 'DESIGN AND REFRESHER | ALE ONLINE  FULL REVIEW', 6000, 'Active'),
(7, 'Architecture', 'F2F All Night', 15000, 'Active');

-- --------------------------------------------------------

--
-- Table structure for table `enrollment_record`
--

CREATE TABLE `enrollment_record` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `en_status` text NOT NULL,
  `en_school` text NOT NULL,
  `en_yeargrad` text DEFAULT NULL,
  `en_employement` text DEFAULT NULL,
  `en_company` text DEFAULT NULL,
  `en_prc` text DEFAULT NULL,
  `en_course` text NOT NULL,
  `en_couse_review` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exam_answer_keys`
--

CREATE TABLE `exam_answer_keys` (
  `id` int(11) NOT NULL,
  `exam_title` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `answer_key` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`answer_key`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_answer_keys`
--

INSERT INTO `exam_answer_keys` (`id`, `exam_title`, `course_name`, `answer_key`, `created_at`) VALUES
(1, 'sample', '', '{\"1\":\"A\",\"2\":\"B\",\"3\":\"C\"}', '2024-09-22 19:25:11'),
(2, 'asdg', '', '[]', '2024-09-22 19:27:16');

-- --------------------------------------------------------

--
-- Table structure for table `mecha_stud`
--

CREATE TABLE `mecha_stud` (
  `student_id` int(11) NOT NULL,
  `review_mecha` varchar(255) DEFAULT NULL,
  `timeslot_section_mecha` varchar(255) DEFAULT NULL,
  `status_stud_mecha` varchar(255) DEFAULT NULL,
  `graduated_mecha` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `omr_results`
--

CREATE TABLE `omr_results` (
  `id` int(11) NOT NULL,
  `filename` varchar(255) DEFAULT NULL,
  `answers` text DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  `user_id` int(11) DEFAULT NULL,
  `course_name` varchar(255) DEFAULT NULL,
  `quiz_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `omr_results`
--

INSERT INTO `omr_results` (`id`, `filename`, `answers`, `date`, `user_id`, `course_name`, `quiz_id`) VALUES
(1, 'test.jpg', 'A,C,D,C,D,A,E,C,E,C,E,C,A,B,B,B,B,A,A,A,B,C,D,A,D,A,C,C,B,C,C,B,D,B,D,B,D,C,C,B,D,B,D,A,C,?,E,B,?,D', '2024-09-22 13:46:05', 26, 'Architecture', 0),
(2, 'test.jpg', 'A,C,D,C,D,A,E,C,E,C,E,C,A,B,B,B,B,A,A,A,B,C,D,A,D,A,C,C,B,C,C,B,D,B,D,B,D,C,C,B,D,B,D,A,C,?,E,B,?,D', '2024-09-23 02:34:32', 26, 'Architecture', 0),
(3, 'test.jpg', 'A,C,D,C,D,A,E,C,E,C,E,C,A,B,B,B,B,A,A,A,B,C,D,A,D,A,C,C,B,C,C,B,D,B,D,B,D,C,C,B,D,B,D,A,C,?,E,B,?,D', '2024-09-23 13:44:09', 30, 'Architecture', 0),
(4, 'test.jpg', 'A,C,D,C,D,A,E,C,E,C,E,C,A,B,B,B,B,A,A,A,B,C,D,A,D,A,C,C,B,C,C,B,D,B,D,B,D,C,C,B,D,B,D,A,C,?,E,B,?,D', '2024-09-23 13:45:10', 30, 'Architecture', 0),
(5, 'test.jpg', 'A,C,D,C,D,A,E,C,E,C,E,C,A,B,B,B,B,A,A,A,B,C,D,A,D,A,C,C,B,C,C,B,D,B,D,B,D,C,C,B,D,B,D,A,C,?,E,B,?,D', '2024-09-23 13:47:21', 30, 'Architecture', 0),
(6, 'test.jpg', 'A,C,D,C,D,A,E,C,E,C,E,C,A,B,B,B,B,A,A,A,B,C,D,A,D,A,C,C,B,C,C,B,D,B,D,B,D,C,C,B,D,B,D,A,C,?,E,B,?,D', '2024-09-23 17:52:52', 26, 'Architecture', 1);

-- --------------------------------------------------------

--
-- Table structure for table `plumber_stud`
--

CREATE TABLE `plumber_stud` (
  `student_id` int(11) NOT NULL,
  `review_plumber` varchar(255) DEFAULT NULL,
  `status_plumber` varchar(255) DEFAULT NULL,
  `graduated_plumber` varchar(255) DEFAULT NULL,
  `graduated_year_plumber` varchar(255) DEFAULT NULL,
  `prc_licences` varchar(255) DEFAULT NULL,
  `facebook_plumber` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `course_name` varchar(255) NOT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `course_name`, `created_by`, `created_at`, `file_path`) VALUES
(1, 'ssdffa', 'Architecture', 26, '2024-09-23 08:26:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `quiz_questions`
--

CREATE TABLE `quiz_questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `correct_answer` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_questions`
--

INSERT INTO `quiz_questions` (`id`, `quiz_id`, `question_text`, `correct_answer`) VALUES
(1, 1, 'hehlesdflhsdf', 'A'),
(2, 1, 'ahdsafds', 'B'),
(3, 1, 'daasdfsd', 'B'),
(4, 1, 'C', 'C'),
(5, 1, 'asdgasdf', 'D');

-- --------------------------------------------------------

--
-- Table structure for table `sectioning`
--

CREATE TABLE `sectioning` (
  `id` int(100) NOT NULL,
  `course_id` text NOT NULL,
  `section_name` text NOT NULL,
  `section_limit` int(11) NOT NULL,
  `section_count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sectioning`
--

INSERT INTO `sectioning` (`id`, `course_id`, `section_name`, `section_limit`, `section_count`) VALUES
(1, '1', 'MORNING | 8:30 AM-12:00 NN', 100, 2),
(2, '3', 'EVENING | 6:00 Pm - 9:00 PM', 500, 3),
(3, '7', 'EVENING | 6:00 Pm - 9:00 PM', 20, 0),
(4, '5', 'MORNING | 8:30 AM-12:00 NN', 20, 0);

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `firstName` varchar(100) NOT NULL,
  `middleName` varchar(100) NOT NULL,
  `lastName` varchar(100) NOT NULL,
  `contactInfo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `courseName` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT 1,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `firstName`, `middleName`, `lastName`, `contactInfo`, `email`, `courseName`, `active`, `user_id`, `section_id`) VALUES
(1, 'lester', 'ken', 'cal', '0912345678', 'mark12@gmail.com', 'Architecture', 1, 23, 4),
(2, 'civil', 'teacher', 'lstname', '0912345678', 'staff3@gmail.com', 'Civil Engineering', 1, 24, 1),
(3, 'sample', 'sample', 'sample', '12345678911', 'mike1233@gmail.com', 'Architecture', 1, 33, NULL),
(4, 'lester', 'sa', 'asdge', '09123565546', 'lesterstaff@gmail.com', 'Architecture', 1, 32, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `staff_section`
--

CREATE TABLE `staff_section` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff_section`
--

INSERT INTO `staff_section` (`id`, `staff_id`, `section_id`) VALUES
(1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `student_quiz_answers`
--

CREATE TABLE `student_quiz_answers` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `answers` text NOT NULL,
  `submitted_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_quiz_answers`
--

INSERT INTO `student_quiz_answers` (`id`, `user_id`, `quiz_id`, `answers`, `submitted_at`) VALUES
(1, 26, 1, 'A,B,D,B,B', '2024-09-23 08:37:58');

-- --------------------------------------------------------

--
-- Table structure for table `student_section`
--

CREATE TABLE `student_section` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL,
  `course_review_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student_section`
--

INSERT INTO `student_section` (`id`, `student_id`, `section_id`, `course_review_id`) VALUES
(1, 87, 1, 1),
(2, 88, 1, 1),
(3, 89, 2, 3),
(4, 90, 2, 3),
(5, 91, 2, 3);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `subjectName` varchar(100) NOT NULL,
  `courseName` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subjectName`, `courseName`, `description`, `created_at`) VALUES
(13, 'Something', 'Architecture', 'heeloo', '2024-06-17 11:51:35'),
(14, 'test sample', 'Architecture', 'testing', '2024-06-17 12:25:16'),
(15, 'test sample', 'Architecture', 'testing', '2024-06-17 12:26:31'),
(16, 'please work', 'Civil Engineering', 'he', '2024-06-17 15:35:30');

-- --------------------------------------------------------

--
-- Table structure for table `subject_contents`
--

CREATE TABLE `subject_contents` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `file_path` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_contents`
--

INSERT INTO `subject_contents` (`id`, `subject_id`, `title`, `content`, `created_at`, `file_path`, `image_path`) VALUES
(49, 1, 'test absolute path', 'tst', '2024-06-12 13:30:39', 'all/uploads/Screenshot_2024-02-16_220300__3_.png', NULL),
(50, 3, 'test sample docs', 'test', '2024-06-12 14:48:04', 'all/uploads/intercompny-inventory-and-FA.docx', NULL),
(51, 16, 'heehe', 'hehheh', '2024-06-17 15:35:43', NULL, NULL),
(52, 16, 'asd', 'asd', '2024-09-15 19:11:19', 'all/uploads/GROUP-H-Case-Study-RetailNowPlatform.docx', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `subject_section`
--

CREATE TABLE `subject_section` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `section_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subject_section`
--

INSERT INTO `subject_section` (`id`, `subject_id`, `section_id`) VALUES
(1, 13, 2),
(2, 14, 3),
(3, 15, 3),
(4, 16, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tb_status`
--

CREATE TABLE `tb_status` (
  `id` int(10) NOT NULL,
  `coursename` text NOT NULL,
  `c_status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_status`
--

INSERT INTO `tb_status` (`id`, `coursename`, `c_status`) VALUES
(1, 'Architecture ', 'New Enrollee'),
(2, 'Architecture ', 'Returning enrollee'),
(3, 'Architecture ', 'Retaker from other Review Center'),
(4, 'Architecture ', 'Currently enrolled in Other Online Review Center'),
(5, 'Architecture ', 'KAYA Scholar'),
(6, 'Mechanical Engineering ', 'New Enrollee'),
(7, 'Mechanical Engineering ', 'Returning enrollee'),
(8, 'Mechanical Engineering ', 'Retaker from other Review Center'),
(9, 'Mechanical Engineering ', 'Currently enrolled in Other Review Center'),
(10, 'Mechanical Engineering ', 'KAYA/Ace+ Scholar'),
(11, 'Master Plumber ', 'New Enrollee'),
(12, 'Master Plumber ', 'Returning Enrollee'),
(13, 'Master Plumber ', 'Org. member (UAP, UAPGA, PICE, PSME,PSIM)\r\n'),
(14, 'Civil Engineering ', 'New Taker / New Enrollee'),
(15, 'Civil Engineering', 'Retaker from other Review Center (Please provide Notice Of Admission or any proof of taking the Board Exam to our facebook Accoun)'),
(16, 'Civil Engineering ', 'Currently enrolled in Other Online Review Center'),
(17, 'Civil Engineering', 'Scholar of Ace+ Review Center');

-- --------------------------------------------------------

--
-- Table structure for table `test_result`
--

CREATE TABLE `test_result` (
  `id` int(11) NOT NULL,
  `omr_result_id` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `total_questions` int(11) NOT NULL,
  `correct_answers` int(11) NOT NULL,
  `incorrect_answers` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_result`
--

INSERT INTO `test_result` (`id`, `omr_result_id`, `score`, `total_questions`, `correct_answers`, `incorrect_answers`, `timestamp`) VALUES
(1, 1, 6, 50, 3, 22, '2024-09-23 05:00:05'),
(2, 2, 6, 50, 3, 22, '2024-09-23 05:00:05'),
(3, 3, 6, 50, 3, 22, '2024-09-23 05:44:13'),
(4, 4, 6, 50, 3, 22, '2024-09-23 05:45:11'),
(5, 5, 6, 50, 3, 22, '2024-09-23 05:47:21'),
(6, 6, 6, 50, 3, 22, '2024-09-23 10:04:40');

-- --------------------------------------------------------

--
-- Table structure for table `users_new`
--

CREATE TABLE `users_new` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `added` datetime NOT NULL DEFAULT current_timestamp(),
  `roles` varchar(50) NOT NULL,
  `password_changed` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_new`
--

INSERT INTO `users_new` (`id`, `username`, `email`, `password`, `active`, `added`, `roles`, `password_changed`) VALUES
(6, 'admin', 'admin@gmail.com', '6c7ca345f63f835cb353ff15bd6c5e052ec08e7a', 1, '2024-05-27 17:54:46', 'Admin', 1),
(21, 'some thing something', 'student2@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-06-08 15:00:10', 'Student', 1),
(22, 'Noime T. Ladigohon', '123@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, '2024-06-09 12:44:15', 'Student', 1),
(23, 'lester kenneth pacman Calzado', 'mark12@gmail.com', '40bd001563085fc35165329ea1ff5c5ecbdbbeef', 1, '2024-06-11 13:29:36', 'Staff', 1),
(24, 'civil teacher lstname', 'staff3@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-06-11 13:45:17', 'Staff', 1),
(26, 'lester kenneth thing sino', 'lester2@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-06-17 14:53:24', 'Student', 1),
(27, 'jybeth thing something', 'mark1@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-06-17 14:59:24', 'Student', 1),
(28, 'mark pacman something', 'mark15@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-06-17 15:09:53', 'Student', 1),
(29, ' hehe hehe', 'lester3@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-06-17 23:36:21', 'Student', 1),
(30, 'meme mememem mememememe', 'lester15@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-07-03 20:59:30', 'Student', 1),
(32, 'lester sa asdge', 'lesterstaff@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-09-23 02:55:44', 'Staff', 1),
(33, 'sample sample sample', 'mike12@gmail.com', 'e831002243c5824b6d4030a9b13833df4bfbf787', 1, '2024-09-23 03:13:15', 'Staff', 0);

-- --------------------------------------------------------

--
-- Table structure for table `user_ledger`
--

CREATE TABLE `user_ledger` (
  `id` int(11) NOT NULL,
  `user_profile_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `particulars` varchar(255) NOT NULL,
  `debit` decimal(10,2) DEFAULT 0.00,
  `credit` decimal(10,2) DEFAULT 0.00,
  `balance` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_ledger`
--

INSERT INTO `user_ledger` (`id`, `user_profile_id`, `date`, `particulars`, `debit`, `credit`, `balance`) VALUES
(3, 81, '2024-06-08', 'Beginning of ledger', 0.00, 0.00, 0.00),
(4, 82, '2024-06-08', 'Beginning of ledger', 0.00, 0.00, 0.00),
(5, 81, '2024-06-08', 'A/D slip', 1000.00, 0.00, 1000.00),
(6, 81, '2024-06-08', 'F2F', 0.00, 500.00, 500.00),
(7, 81, '2024-06-08', 'F2F', 0.00, 500.00, 0.00),
(8, 81, '2024-06-08', 'A/D slip', 700.00, 0.00, 700.00),
(9, 81, '2024-06-08', 'A/D slip', 700.00, 0.00, 1400.00),
(10, 81, '2024-06-08', 'F2F', 0.00, 500.00, 900.00),
(11, 81, '2024-06-09', 'F2F', 0.00, 500.00, 400.00),
(12, 81, '2024-06-09', 'A/D slip', 6000.00, 0.00, 6400.00),
(13, 89, '2024-07-03', 'beginning of ledger', 0.00, 0.00, 0.00),
(14, 90, '2024-07-04', 'Beginning of Ledger', 0.00, 0.00, 0.00),
(15, 91, '2024-07-04', 'Beginning of Ledger', 0.00, 0.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(255) NOT NULL,
  `fName` varchar(255) NOT NULL,
  `mName` varchar(255) NOT NULL,
  `lName` varchar(255) NOT NULL,
  `gender` varchar(50) NOT NULL,
  `conNumb` varchar(50) NOT NULL,
  `email_stud` varchar(255) NOT NULL,
  `pName` varchar(255) NOT NULL,
  `bDate` date NOT NULL,
  `address_stud` varchar(255) NOT NULL,
  `courseName` text NOT NULL,
  `facebook` text NOT NULL,
  `account_status` int(11) NOT NULL DEFAULT 0,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `section_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_profile`
--

INSERT INTO `user_profile` (`id`, `fName`, `mName`, `lName`, `gender`, `conNumb`, `email_stud`, `pName`, `bDate`, `address_stud`, `courseName`, `facebook`, `account_status`, `user_id`, `section_id`) VALUES
(82, 'some', 'thing', 'something', 'Male', '0987654333', 'onlyme@gmail.com', 'Parentname', '2024-06-10', 'iloilo city', 'Civil Engineering', 'soemthing.backspace', 0, 21, NULL),
(83, 'lester kenneth', 'thing', 'sino', 'Male', '0987654333', 'mark@gmail.com', 'thingsome', '2024-07-01', 'iloilo city', 'Architecture', 'soemthing.backspace1', 0, 26, NULL),
(84, 'jybeth', 'thing', 'something', 'Male', '0987654333', 'mark1@gmail.com', 'Parentname', '2024-06-09', 'iloilo city', 'Civil Engineering', 'soemthing.backspace', 0, 27, NULL),
(85, 'Noime', 'T.', 'Ladigohon', 'Male', '12345678', '123@gmail.com', 'gjh', '2024-06-27', 'Brgy. Bagacay, Pototan, Iloilo, Philippines', 'Architecture', '123@gmail.com', 0, 22, NULL),
(86, 'lester kenneth', 'pacman', 'Calzado', 'Male', '0987654333', 'lester@gmail.com', 'Parentname', '2024-06-11', 'iloilo city', 'Architecture', 'soemthing.backspace', 0, 23, NULL),
(87, 'mark', 'pacman', 'something', 'Male', '0966666666', 'mark15@gmail.com', 'Parentname', '2024-06-11', 'iloilo city', 'Civil Engineering', 'soemthing.backspace', 0, 28, NULL),
(88, '', 'hehe', 'hehe', 'Male', 'hehe', 'lester3@gmail.com', 'hehe', '2024-05-28', 'ilil', 'Civil Engineering', 'hehe', 0, 29, 1),
(89, 'meme', 'mememem', 'mememememe', 'Male', '09234567899', 'lester15@gmail.com', 'memememmemee', '2024-06-18', 'doon banda', 'Architecture', 'me', 0, 30, 2),
(90, 'hester menneth', 'galdfer', 'Malenia', 'Male', '09123456789', 'burgerking@gmail.com', 'hello world', '2024-07-04', 'SM city', 'Architecture', 'hehe', 0, NULL, 2);

-- --------------------------------------------------------

--
-- Table structure for table `user_sidebar_preferences`
--

CREATE TABLE `user_sidebar_preferences` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `sidebar_item` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_sidebar_preferences`
--

INSERT INTO `user_sidebar_preferences` (`id`, `user_id`, `sidebar_item`) VALUES
(26, 21, 'dashboard'),
(27, 21, 'maintenance'),
(28, 21, 'profile'),
(29, 22, 'dashboard'),
(30, 22, 'maintenance'),
(31, 22, 'profile'),
(66, 6, 'people'),
(67, 6, 'course'),
(68, 6, 'announcement'),
(69, 6, 'architecture'),
(70, 6, 'civil_engi'),
(71, 6, 'mecha_engi'),
(72, 24, 'maintenance'),
(73, 24, 'profile'),
(74, 24, 'architecture'),
(75, 24, 'civil_engi'),
(76, 24, 'mecha_engi'),
(77, 20, 'dashboard'),
(78, 20, 'maintenance'),
(79, 20, 'profile'),
(80, 20, 'course'),
(81, 23, 'maintenance'),
(82, 23, 'profile'),
(83, 23, 'architecture'),
(84, 23, 'civil_engi'),
(85, 23, 'mecha_engi');

-- --------------------------------------------------------

--
-- Table structure for table `user_staff`
--

CREATE TABLE `user_staff` (
  `id` int(11) NOT NULL,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `staff_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_staff`
--

INSERT INTO `user_staff` (`id`, `user_id`, `staff_id`) VALUES
(1, 23, 1),
(2, 24, 2);

-- --------------------------------------------------------

--
-- Table structure for table `waitlist`
--

CREATE TABLE `waitlist` (
  `waitlist_id` int(11) NOT NULL,
  `user_profile_id` int(11) DEFAULT NULL,
  `payment_status` enum('Pending','Paid') NOT NULL DEFAULT 'Pending',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `waitlist`
--

INSERT INTO `waitlist` (`waitlist_id`, `user_profile_id`, `payment_status`, `registration_date`) VALUES
(12, 82, 'Paid', '2024-06-07 19:34:38'),
(13, 83, 'Paid', '2024-06-09 10:12:36'),
(14, 84, 'Paid', '2024-06-09 12:57:19'),
(15, 85, 'Paid', '2024-06-09 16:43:06'),
(16, 86, 'Paid', '2024-06-10 21:08:02'),
(17, 87, 'Paid', '2024-06-17 07:06:09'),
(18, 88, 'Paid', '2024-06-17 15:34:34'),
(19, 89, 'Paid', '2024-06-17 16:08:19'),
(20, 90, 'Paid', '2024-07-04 07:40:07'),
(21, 91, 'Paid', '2024-07-04 15:03:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `answer_key`
--
ALTER TABLE `answer_key`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `arki_stud`
--
ALTER TABLE `arki_stud`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `civil_stud`
--
ALTER TABLE `civil_stud`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `course_reviews`
--
ALTER TABLE `course_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `enrollment_record`
--
ALTER TABLE `enrollment_record`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_answer_keys`
--
ALTER TABLE `exam_answer_keys`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mecha_stud`
--
ALTER TABLE `mecha_stud`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `omr_results`
--
ALTER TABLE `omr_results`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `plumber_stud`
--
ALTER TABLE `plumber_stud`
  ADD PRIMARY KEY (`student_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sectioning`
--
ALTER TABLE `sectioning`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `section_id` (`section_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `staff_section`
--
ALTER TABLE `staff_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section-id` (`section_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `student_quiz_answers`
--
ALTER TABLE `student_quiz_answers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_section`
--
ALTER TABLE `student_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `section_id` (`section_id`),
  ADD KEY `fk_student_section_course_reviews` (`course_review_id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subject_contents`
--
ALTER TABLE `subject_contents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `subject_section`
--
ALTER TABLE `subject_section`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `section_id` (`section_id`);

--
-- Indexes for table `tb_status`
--
ALTER TABLE `tb_status`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_result`
--
ALTER TABLE `test_result`
  ADD PRIMARY KEY (`id`),
  ADD KEY `omr_result_id` (`omr_result_id`);

--
-- Indexes for table `users_new`
--
ALTER TABLE `users_new`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_ledger`
--
ALTER TABLE `user_ledger`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_profile_id` (`user_profile_id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`user_id`),
  ADD KEY `fk_section_id_profile` (`section_id`);

--
-- Indexes for table `user_sidebar_preferences`
--
ALTER TABLE `user_sidebar_preferences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `user_staff`
--
ALTER TABLE `user_staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `waitlist`
--
ALTER TABLE `waitlist`
  ADD PRIMARY KEY (`waitlist_id`),
  ADD KEY `user_profile_id` (`user_profile_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `answer_key`
--
ALTER TABLE `answer_key`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `course_reviews`
--
ALTER TABLE `course_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enrollment_record`
--
ALTER TABLE `enrollment_record`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exam_answer_keys`
--
ALTER TABLE `exam_answer_keys`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `omr_results`
--
ALTER TABLE `omr_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quiz_questions`
--
ALTER TABLE `quiz_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sectioning`
--
ALTER TABLE `sectioning`
  MODIFY `id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `staff_section`
--
ALTER TABLE `staff_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_quiz_answers`
--
ALTER TABLE `student_quiz_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `student_section`
--
ALTER TABLE `student_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `subject_contents`
--
ALTER TABLE `subject_contents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT for table `subject_section`
--
ALTER TABLE `subject_section`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_status`
--
ALTER TABLE `tb_status`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `test_result`
--
ALTER TABLE `test_result`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users_new`
--
ALTER TABLE `users_new`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `user_ledger`
--
ALTER TABLE `user_ledger`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=92;

--
-- AUTO_INCREMENT for table `user_sidebar_preferences`
--
ALTER TABLE `user_sidebar_preferences`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT for table `user_staff`
--
ALTER TABLE `user_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `waitlist`
--
ALTER TABLE `waitlist`
  MODIFY `waitlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `arki_stud`
--
ALTER TABLE `arki_stud`
  ADD CONSTRAINT `arki_stud_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `civil_stud`
--
ALTER TABLE `civil_stud`
  ADD CONSTRAINT `civil_stud_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mecha_stud`
--
ALTER TABLE `mecha_stud`
  ADD CONSTRAINT `mecha_stud_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `plumber_stud`
--
ALTER TABLE `plumber_stud`
  ADD CONSTRAINT `plumber_stud_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user_profile` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_section_id` FOREIGN KEY (`section_id`) REFERENCES `sectioning` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_staff_user_id` FOREIGN KEY (`user_id`) REFERENCES `users_new` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `staff_section`
--
ALTER TABLE `staff_section`
  ADD CONSTRAINT `fk_staff_section_ibfk_1` FOREIGN KEY (`section_id`) REFERENCES `sectioning` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_staff_section_ibfk_2` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `student_section`
--
ALTER TABLE `student_section`
  ADD CONSTRAINT `course_assignments_ibk_3` FOREIGN KEY (`section_id`) REFERENCES `sectioning` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_section_ibk_1` FOREIGN KEY (`student_id`) REFERENCES `user_profile` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_student_section_ibk_2` FOREIGN KEY (`course_review_id`) REFERENCES `course_reviews` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `subject_section`
--
ALTER TABLE `subject_section`
  ADD CONSTRAINT `subject_section_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `subject_section_ibfk_2` FOREIGN KEY (`section_id`) REFERENCES `sectioning` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `test_result`
--
ALTER TABLE `test_result`
  ADD CONSTRAINT `test_result_ibfk_1` FOREIGN KEY (`omr_result_id`) REFERENCES `omr_results` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD CONSTRAINT `fk_section_id_profile` FOREIGN KEY (`section_id`) REFERENCES `sectioning` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`user_id`) REFERENCES `users_new` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
