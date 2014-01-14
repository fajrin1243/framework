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
(2, 'withdrawal_gateway', 'member_gateway_lists', 'Penarikan Dana', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(3, 'auto_approval_invitation', 'allow,confirm', 'Penerimaan Permintaan Contact Otomatis', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(4, 'auto_approval_transfer', 'allow,confirm', 'Penerimaan Permintaan Contact Otomatis', 'account_setting', '2013-11-15 00:00:00', 'Active'),
(5, 'notify_activity', 'email,hp', 'Beritahu Saya Saat Terdapat Aktifitas Transaksi', 'account_setting', '2013-11-15 00:00:00', 'Inactive'),
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
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Dumping data untuk tabel `parameter_value`
--

INSERT INTO `parameter_value` (`id`, `user_id`, `parameter_id`, `value`, `last_modified`) VALUES
(1, 12, 1, '1', '0000-00-00 00:00:00'),
(2, 12, 3, 'allow', '0000-00-00 00:00:00'),
(3, 12, 4, 'allow', '0000-00-00 00:00:00'),
(4, 12, 5, '1', '0000-00-00 00:00:00'),
(5, 12, 6, '10000000', '0000-00-00 00:00:00'),
(6, 12, 8, '100000', '0000-00-00 00:00:00'),
(7, 12, 9, '100000', '0000-00-00 00:00:00'),
(8, 14, 1, 'Pribadi', '0000-00-00 00:00:00'),
(9, 14, 2, '', '0000-00-00 00:00:00'),
(10, 14, 3, 'allow', '0000-00-00 00:00:00'),
(11, 14, 4, 'allow', '0000-00-00 00:00:00'),
(12, 14, 5, 'email', '0000-00-00 00:00:00'),
(13, 14, 6, '', '0000-00-00 00:00:00'),
(14, 14, 8, '1', '0000-00-00 00:00:00'),
(15, 14, 9, '1', '0000-00-00 00:00:00');

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
('qu6nbu6pbo68n85uhgkpqaqa71', 'Chrome;32.0.1700.72;', '{"user_id":"jYomT/zPk/FC0HoDBdRCi9HPsG9+4/HUbGJFfS8CmMg=","email":"haiPAi/PJzYOkpe5yjlKlPZzw/0pLJa+cuO4Tv9vncIQ3sEW//i52K4bxz48s6NY","SF_LANG":"Ny06mfA4pmqhaF6E0WapT6z0xn0mn2p2pg2xrbVyxEg="}', '2014-01-14 14:48:20');

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
  `name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `tipe_member_id` int(11) NOT NULL,
  `last_activity` datetime NOT NULL,
  `last_modified` datetime NOT NULL,
  `status` enum('Active','Inactive','Archived') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;

--
-- Dumping data untuk tabel `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `name`, `email`, `tipe_member_id`, `last_activity`, `last_modified`, `status`) VALUES
(1, 'temals', 'cbd23b198a963e09ea0eda704cb6ff84', 'temals', 'temals.mulyadi@gmail.com', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00', 'Active'),
(3, 'member', 'aa08769cdcb26674c6706093503ff0a3', 'member juga', 'member@member.com', 4, '0000-00-00 00:00:00', '2014-01-14 00:00:00', 'Active'),
(4, 'member2', '88ed421f060aadcacbd63f28d889797f', 'nama', 'member2@email.com', 4, '2013-09-17 00:00:00', '2013-09-17 00:00:00', 'Active'),
(8, 'member3', '3ef4802d8a37022fd187fbd829d1c4d6', 'member tiga', 'member3@email.com', 4, '0000-00-00 00:00:00', '2014-01-02 00:00:00', 'Inactive'),
(9, 'member4', 'a998123003066ac9fa7de4b100e7c4bc', 'member4', 'member4@email.com', 4, '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(12, 'temals', 'ec80be4898655fcf42d8669dde6cb6b2', 'Slamet', 'tema.meta@gmail.com', 4, '0000-00-00 00:00:00', '2013-12-13 07:29:14', 'Inactive'),
(13, 'riri', 'c740d6848b6a342dcc26c177ea2c49fe', 'riri', 'riri@riri.com', 4, '0000-00-00 00:00:00', '2013-12-16 07:19:41', 'Inactive'),
(14, '', 'c7764cfed23c5ca3bb393308a0da2306', '', 'member1@member.com', 4, '0000-00-00 00:00:00', '2013-12-31 11:01:13', 'Inactive'),
(15, 'member_baru', 'e10adc3949ba59abbe56e057f20f883e', 'member baru', 'member@baru.com', 4, '0000-00-00 00:00:00', '2014-01-02 00:00:00', 'Inactive');
