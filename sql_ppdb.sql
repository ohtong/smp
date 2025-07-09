/*
SQLyog Professional v12.5.1 (64 bit)
MySQL - 10.4.25-MariaDB : Database - db_egov
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_egov` /*!40100 DEFAULT CHARACTER SET utf8mb4 */;

USE `db_egov`;

/*Table structure for table `acara` */

DROP TABLE IF EXISTS `acara`;

CREATE TABLE `acara` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `tanggal` date NOT NULL,
  `lokasi` varchar(255) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `acara` */

/*Table structure for table `guru` */

DROP TABLE IF EXISTS `guru`;

CREATE TABLE `guru` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(255) DEFAULT NULL,
  `nip` varchar(50) DEFAULT NULL,
  `mapel` varchar(100) DEFAULT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `guru` */

/*Table structure for table `konseling` */

DROP TABLE IF EXISTS `konseling`;

CREATE TABLE `konseling` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `topik` text DEFAULT NULL,
  `solusi` text DEFAULT NULL,
  `status` enum('Pending','Selesai') DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `konseling` */

/*Table structure for table `kontak` */

DROP TABLE IF EXISTS `kontak`;

CREATE TABLE `kontak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `pesan` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `kontak` */

/*Table structure for table `pelanggaran` */

DROP TABLE IF EXISTS `pelanggaran`;

CREATE TABLE `pelanggaran` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_siswa` int(11) DEFAULT NULL,
  `jenis` varchar(255) DEFAULT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `poin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

/*Data for the table `pelanggaran` */

/*Table structure for table `ppdb` */

DROP TABLE IF EXISTS `ppdb`;

CREATE TABLE `ppdb` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `nama_lengkap` varchar(100) DEFAULT NULL,
  `nisn` varchar(20) DEFAULT NULL,
  `nama_orang_tua` varchar(255) DEFAULT NULL,
  `asal_sekolah` varchar(100) DEFAULT NULL,
  `alamat` text DEFAULT NULL,
  `sekolah_pilihan` enum('smps_ibnu_sina','smps_02_ibnu_sina') DEFAULT NULL,
  `status` enum('pending','diterima','ditolak') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `no_hp` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `ppdb_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4;

/*Data for the table `ppdb` */

insert  into `ppdb`(`id`,`user_id`,`nama_lengkap`,`nisn`,`nama_orang_tua`,`asal_sekolah`,`alamat`,`sekolah_pilihan`,`status`,`created_at`,`no_hp`) values 
(4,1,'nazri hamdi','08126134',NULL,'SDIT IBNU SINA','BIDA AYU','smps_02_ibnu_sina','ditolak','2025-05-19 17:28:02',NULL),
(5,1,'thuthuk','123456',NULL,'lubuk pakam','luto','smps_ibnu_sina','ditolak','2025-05-19 17:39:48',NULL),
(6,3,'raka','123456789',NULL,'SDIT IBNU SINA','lampung','smps_02_ibnu_sina','ditolak','2025-05-19 18:21:49',NULL),
(7,1,'hamdi','123','tong','123','123','smps_02_ibnu_sina','ditolak','2025-05-20 16:12:32',NULL),
(8,1,'hamdi','123','ohtong','nongsa','bida ayu','smps_ibnu_sina','diterima','2025-05-20 16:38:15',NULL),
(9,7,'asdasd','asd','asd','asd','asfd','smps_ibnu_sina','ditolak','2025-06-19 22:50:13',NULL);

/*Table structure for table `users` */

DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) DEFAULT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('admin','siswa') DEFAULT NULL,
  `sekolah` enum('smps_ibnu_sina','smps_02_ibnu_sina') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

/*Data for the table `users` */

insert  into `users`(`id`,`nama`,`username`,`password`,`role`,`sekolah`,`created_at`) values 
(1,'hamdi','hamdi','$2y$10$37H2a2CdqNsnZRkpJrFHuO/mU0gU/R.S8YxkIJjb2TUC4eCDEO6Hi','siswa','smps_ibnu_sina','2025-05-19 16:47:32'),
(2,'ohtong','ohtong','$2y$10$zd7G0eGhWFd50SXpmqJmJOj217vHCCi7NLFobHynsSuPKKrwTKH.2','admin','smps_ibnu_sina','2025-05-19 16:50:45'),
(3,'raka','raka','$2y$10$yoH7qG7lJBTzHYU3.SY0ueX71ukGUkvuIcEmJ0JxY39tT3G.j.sjK','siswa','smps_02_ibnu_sina','2025-05-19 18:21:18'),
(4,'','','$2y$10$m2Oe2nhy3aenHrc2xBHLG.igatj04dFJAPkZ9a7WaY4TrihciQ4my',NULL,NULL,'2025-05-21 19:56:59'),
(5,'rufli m','tufli','$2y$10$sLPIKS17kWciU6DbuK6j4.eUh7b/pg2UMdNO6wiRqyqx0BYnn8XDu','siswa','smps_02_ibnu_sina','2025-06-19 22:47:37'),
(6,'rufli','rufli123','$2y$10$adOpNHDFJwk4T/kfp1X99.l8Ov6UB08/rj0SO4cmv.cfGz7TtnxlK','siswa','smps_ibnu_sina','2025-06-19 22:48:05'),
(7,'rufli','rufli','$2y$10$jgpr4ByrU2fIlBSJzRnds.ZgNfIXc4APvDvDaqV588glm4fRtG.jO','siswa','smps_02_ibnu_sina','2025-06-19 22:49:43');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
