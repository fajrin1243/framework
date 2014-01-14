-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Inang: 127.0.0.1
-- Waktu pembuatan: 01 Des 2013 pada 11.59
-- Versi Server: 5.5.27
-- Versi PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `opsipay`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(150) NOT NULL,
  `config` varchar(150) NOT NULL,
  `value` text NOT NULL,
  `status` enum('Active','Incative','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `config`
--

INSERT INTO `config` (`id`, `type`, `config`, `value`, `status`) VALUES
(1, 'connect', 'system_id', '492ec3cc5ae0435097d903278442bf5a', 'Active'),
(2, 'connect', 'access', '492ec3cc5ae0435097d903278442bf5a', 'Active'),
(3, 'connect', 'allow_client_update', '0', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `connect`
--

CREATE TABLE IF NOT EXISTS `connect` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(150) NOT NULL,
  `connect_id` varchar(50) NOT NULL,
  `connect_key` varchar(50) NOT NULL,
  `type` enum('Request','Response') NOT NULL,
  `token` text NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Pending','Allow','Deny') NOT NULL DEFAULT 'Pending',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `connect`
--

INSERT INTO `connect` (`id`, `domain`, `connect_id`, `connect_key`, `type`, `token`, `last_modified`, `status`) VALUES
(1, 'http://localhost', 'siapkah', 'anda', 'Request', '41360dc099d783df76c00912f641298b', '2013-10-03 17:45:35', 'Allow');

-- --------------------------------------------------------

--
-- Struktur dari tabel `parameter`
--

CREATE TABLE IF NOT EXISTS `parameter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `option` text NOT NULL,
  `description` text NOT NULL,
  `category` varchar(150) NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Active','Inactive','Trash','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data untuk tabel `parameter`
--

INSERT INTO `parameter` (`id`, `name`, `option`, `description`, `category`, `last_modified`, `status`) VALUES
(1, 'account_type', 'Pribadi,Bisnis', 'Pilihan Tipe Account, penggunaan pribadi untuk personal atau untuk bisnis', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(2, 'withdrawal_gateway', 'pay_member_gateway_lists', 'Penarikan Dana', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(3, 'auto_approval_invitation', 'allow,confirm', 'Penerimaan Permintaan Contact Otomatis', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(4, 'auto_approval_transfer', 'allow,confirm', 'Penerimaan Permintaan Contact Otomatis', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(5, 'notify_activity', 'email,hp', 'Beritahu Saya Saat Terdapat Aktifitas Transaksi', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(6, 'limit_transfer', '', 'Batasan Transfer', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(8, 'rule_forward_transfer', '', 'Menambahkan Rule Teruskan Dana', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(9, 'rule_approval_transfer', '', 'Menambahkan Rule Penerimaan Dana Transfer', 'account_setting', '2013-11-15 00:00:00', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `parameter_sign`
--

CREATE TABLE IF NOT EXISTS `parameter_sign` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `tipe_member_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Active','Inactive','Trash','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data untuk tabel `parameter_sign`
--

INSERT INTO `parameter_sign` (`id`, `user_id`, `tipe_member_id`, `parameter_id`, `last_modified`, `status`) VALUES
(1, 0, 4, 1, '2013-11-15 00:00:00', 'Active'),
(2, 0, 4, 2, '2013-11-15 00:00:00', 'Active'),
(3, 0, 4, 3, '2013-11-15 00:00:00', 'Active'),
(4, 0, 4, 4, '2013-11-15 00:00:00', 'Active'),
(5, 0, 4, 5, '2013-11-15 00:00:00', 'Active'),
(6, 0, 4, 6, '2013-11-15 00:00:00', 'Active'),
(7, 0, 4, 7, '2013-11-15 00:00:00', 'Active'),
(8, 0, 4, 8, '2013-11-15 00:00:00', 'Active'),
(9, 0, 4, 9, '2013-11-15 00:00:00', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `parameter_value`
--

CREATE TABLE IF NOT EXISTS `parameter_value` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `parameter_id` int(11) NOT NULL,
  `value` text NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data untuk tabel `parameter_value`
--

INSERT INTO `parameter_value` (`id`, `user_id`, `parameter_id`, `value`, `last_modified`) VALUES
(1, 3, 1, 'Pribadi', '0000-00-00 00:00:00'),
(2, 3, 2, '1', '0000-00-00 00:00:00'),
(3, 3, 3, 'allow', '0000-00-00 00:00:00'),
(4, 3, 4, 'allow', '0000-00-00 00:00:00'),
(5, 3, 5, 'email', '0000-00-00 00:00:00'),
(6, 3, 6, '10000000', '0000-00-00 00:00:00'),
(7, 3, 8, '', '0000-00-00 00:00:00'),
(8, 3, 9, '', '0000-00-00 00:00:00'),
(9, 4, 1, 'Pribadi', '0000-00-00 00:00:00'),
(10, 4, 2, '3', '0000-00-00 00:00:00'),
(11, 4, 3, 'allow', '0000-00-00 00:00:00'),
(12, 4, 4, 'allow', '0000-00-00 00:00:00'),
(13, 4, 5, 'email', '0000-00-00 00:00:00'),
(14, 4, 6, '5000000', '0000-00-00 00:00:00'),
(15, 4, 8, '', '0000-00-00 00:00:00'),
(16, 4, 9, '', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_account`
--

CREATE TABLE IF NOT EXISTS `pay_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `account` varchar(150) NOT NULL,
  `account_id` int(8) NOT NULL,
  `pin` varchar(15) NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Active','Inactive') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `pay_account`
--

INSERT INTO `pay_account` (`id`, `user_id`, `nama`, `account`, `account_id`, `pin`, `last_modified`, `status`) VALUES
(1, 3, 'Slamet Mulyadi', 'temals', 0, '887788', '0000-00-00 00:00:00', 'Active'),
(2, 4, 'member dua', 'member2', 0, '121314', '0000-00-00 00:00:00', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_balance`
--

CREATE TABLE IF NOT EXISTS `pay_balance` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `balance` float NOT NULL,
  `currency_id` int(11) NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_contact`
--

CREATE TABLE IF NOT EXISTS `pay_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `contact_id` int(11) NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Pending','Approve','Reject','Cancel') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_currency`
--

CREATE TABLE IF NOT EXISTS `pay_currency` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `currency` varchar(150) NOT NULL,
  `buy` float NOT NULL,
  `sell` float NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Active','Inactive','Trash','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_gateway`
--

CREATE TABLE IF NOT EXISTS `pay_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gateway_type_id` varchar(150) NOT NULL,
  `gateway` varchar(150) NOT NULL,
  `account` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Active','Inactive','Trash') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `pay_gateway`
--

INSERT INTO `pay_gateway` (`id`, `gateway_type_id`, `gateway`, `account`, `description`, `status`) VALUES
(1, '1', 'Bank BCA', '188821829', 'Transaksi Bank Lokal, Bank BCA', 'Active'),
(2, '2', 'Paypal', '1921987', 'Paypal Account', 'Active'),
(3, '1', 'Bank Mandiri', '182383173', 'Bank Mandiri', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_gateway_type`
--

CREATE TABLE IF NOT EXISTS `pay_gateway_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Active','Inactive','Trashed','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `pay_gateway_type`
--

INSERT INTO `pay_gateway_type` (`id`, `type`, `description`, `status`) VALUES
(1, 'Bank Lokal', 'Transaksi Bank Lokal', 'Active'),
(2, 'Paypal', 'Transaksi Melalui Paypal', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_member_gateway`
--

CREATE TABLE IF NOT EXISTS `pay_member_gateway` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `gateway_id` int(11) NOT NULL,
  `nama` varchar(150) NOT NULL,
  `account` varchar(50) NOT NULL,
  `status` enum('Active','Inactive','Trash') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data untuk tabel `pay_member_gateway`
--

INSERT INTO `pay_member_gateway` (`id`, `user_id`, `gateway_id`, `nama`, `account`, `status`) VALUES
(1, 3, 1, 'Slamet Mulyadi', '123123', 'Active'),
(2, 4, 3, 'member dua', '777882910', 'Active'),
(3, 4, 1, 'member dua', '12839100', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_member_payment`
--

CREATE TABLE IF NOT EXISTS `pay_member_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment_id` int(11) NOT NULL,
  `account` varchar(150) NOT NULL,
  `status` enum('Active','inactive','Trash','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_payment`
--

CREATE TABLE IF NOT EXISTS `pay_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `payment` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `status` enum('Active','Inactive','Trash') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `pay_transaction`
--

CREATE TABLE IF NOT EXISTS `pay_transaction` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `destination_id` int(11) NOT NULL,
  `type` varchar(150) NOT NULL,
  `nominal` int(11) NOT NULL,
  `currency_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `status` enum('Pending','Success','Failed','Reject','Cancel') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `privilege`
--

CREATE TABLE IF NOT EXISTS `privilege` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` enum('tipe_member_id','user_id') NOT NULL,
  `type_id` int(11) NOT NULL,
  `menu` varchar(150) NOT NULL,
  `action` varchar(150) NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data untuk tabel `privilege`
--

INSERT INTO `privilege` (`id`, `type`, `type_id`, `menu`, `action`, `last_modified`) VALUES
(1, 'user_id', 1, 'app', 'view', '2013-10-23 20:06:17');

-- --------------------------------------------------------

--
-- Struktur dari tabel `session`
--

CREATE TABLE IF NOT EXISTS `session` (
  `session_id` varchar(50) NOT NULL,
  `user_agent` varchar(50) NOT NULL,
  `data` text NOT NULL,
  `date` datetime NOT NULL,
  UNIQUE KEY `session_id` (`session_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `session`
--

INSERT INTO `session` (`session_id`, `user_agent`, `data`, `date`) VALUES
('0hf3kv24jbokus5121oolhhf71', 'Chrome;31.0.1650.57;', '{"SF_LANG":"u/5b89zySIk/PjrLymjZAbuBxVwkP8yzc2AhOtv9guE=","user_id":"J3xALVJhpuUefnIpdFtMQULoP9JUxirDSwO5bck9CDk=","email":"KvD7n99IXfmVt4O/NsUXOfi93mpsl0BjVyBpRtXKY0ywaQyMhPgv0WBbY0sk2veH"}', '2013-11-29 07:30:10');

-- --------------------------------------------------------

--
-- Struktur dari tabel `set_request`
--

CREATE TABLE IF NOT EXISTS `set_request` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `domain` varchar(150) NOT NULL,
  `request` varchar(50) NOT NULL,
  `values` text NOT NULL,
  `last_modified` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tipe_member`
--

CREATE TABLE IF NOT EXISTS `tipe_member` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipe_member` varchar(150) NOT NULL,
  `status` enum('Active','Inactive','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data untuk tabel `tipe_member`
--

INSERT INTO `tipe_member` (`id`, `tipe_member`, `status`) VALUES
(1, 'Super User', 'Active'),
(2, 'Administrator', 'Active'),
(3, 'Manager', 'Active'),
(4, 'Register', 'Active');

-- --------------------------------------------------------

--
-- Struktur dari tabel `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(150) NOT NULL,
  `password` text NOT NULL,
  `nama` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tipe_member_id` int(11) NOT NULL,
  `hp` varchar(150) NOT NULL,
  `alamat` text NOT NULL,
  `last_activity` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Active','Inactive','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `nama`, `email`, `tipe_member_id`, `hp`, `alamat`, `last_activity`, `last_modified`, `status`) VALUES
(1, 'temals', 'cbd23b198a963e09ea0eda704cb6ff84', 'temals', 'temals.mulyadi@gmail.com', 1, '08111466626', 'jl. jatikramat indah II', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Active'),
(3, 'member', 'aa08769cdcb26674c6706093503ff0a3', 'member', 'member@member.com', 4, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Active'),
(4, 'member2', '88ed421f060aadcacbd63f28d889797f', 'nama', 'member2@email.com', 4, '08234928', 'Jl. Capung Raya tragedi', '2013-09-17 00:00:00', '2013-09-17 00:00:00', 'Active'),
(8, 'member3', '3ef4802d8a37022fd187fbd829d1c4d6', 'member 3', 'member3@email.com', 4, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(9, 'member4', 'a998123003066ac9fa7de4b100e7c4bc', 'member4', 'member4@email.com', 4, '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
