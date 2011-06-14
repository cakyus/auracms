-- phpMyAdmin SQL Dump
-- version 3.2.0.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Aug 28, 2010 at 05:02 
-- Server version: 5.1.37
-- PHP Version: 5.3.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `auracms_ver2.3`
--

-- --------------------------------------------------------

--
-- Table structure for table `actions`
--

CREATE TABLE IF NOT EXISTS `actions` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `modul` varchar(20) NOT NULL DEFAULT '',
  `posisi` int(1) NOT NULL DEFAULT '0',
  `order` int(3) NOT NULL DEFAULT '0',
  `modul_id` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `modul_id` (`modul_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Dumping data for table `actions`
--

INSERT INTO `actions` (`id`, `modul`, `posisi`, `order`, `modul_id`) VALUES
(1, 'lyrics', 0, 0, 4),
(2, 'lyrics', 0, 1, 17),
(3, 'lyrics', 0, 2, 5),
(4, 'lyrics', 1, 5, 16),
(5, 'lyrics', 1, 1, 8),
(6, 'lyrics', 1, 2, 21),
(7, 'lyrics', 1, 3, 25),
(8, 'lyrics', 1, 4, 19),
(10, 'download', 0, 0, 4),
(11, 'download', 0, 1, 17),
(12, 'download', 0, 2, 5),
(13, 'download', 0, 3, 21),
(14, 'download', 1, 0, 18),
(16, 'download', 1, 2, 19),
(18, 'download', 1, 4, 8),
(19, 'download', 1, 5, 25),
(20, 'lyrics', 1, 6, 31),
(31, 'download', 1, 1, 36),
(33, 'download', 1, 0, 37),
(34, 'lyrics', 1, 7, 37);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu` varchar(30) NOT NULL DEFAULT '',
  `url` varchar(60) NOT NULL DEFAULT '',
  `mod` int(1) NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `menu`, `url`, `mod`, `ordering`) VALUES
(1, 'Beranda Admin', 'admin.php', 0, 1),
(2, 'Edit Admin', 'admin_info.php', 0, 2),
(3, 'Menu Manager', 'admin_menu.php', 0, 3),
(4, 'Pages Manager', 'admin_pages.php', 0, 4),
(5, 'Polling Manager', 'polling', 1, -1),
(8, 'News Manager', 'news', 1, 5),
(9, 'Buku Tamu Manager', 'guestbook', 1, 6),
(11, 'Modul Manager', 'admin_modul.php', 0, 7),
(13, 'Files Manager', 'admin_files.php', 0, 8),
(14, 'Download Manager', 'download', 1, 9),
(15, 'Links Manager', 'links', 1, 10),
(16, 'Logout', '?aksi=logout', 0, 16),
(17, 'Shoutbox Manager', 'shoutbox', 1, 11),
(25, 'Calender Manager', 'calendar', 1, 12),
(23, 'Gallery Manager', 'gallery', 1, 13),
(21, 'Actions Manager', 'admin_actions.php', 0, 14),
(42, 'User Manager', 'admin_users.php', 0, 15);

-- --------------------------------------------------------

--
-- Table structure for table `artikel`
--

CREATE TABLE IF NOT EXISTS `artikel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(100) NOT NULL DEFAULT '',
  `konten` text NOT NULL,
  `user` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `tgl` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `publikasi` int(1) NOT NULL DEFAULT '0',
  `topik` tinyint(11) NOT NULL DEFAULT '0',
  `gambar` text NOT NULL,
  `hits` int(250) NOT NULL DEFAULT '0',
  `tags` varchar(255) NOT NULL DEFAULT '',
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `topik` (`topik`),
  KEY `tags` (`tags`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `artikel`
--

INSERT INTO `artikel` (`id`, `judul`, `konten`, `user`, `email`, `tgl`, `publikasi`, `topik`, `gambar`, `hits`, `tags`, `seftitle`) VALUES
(1, 'Tarik Dubes RI, Shock Therapy untuk Malaysia', '<p><strong>Besar Indonesia untuk Malaysia dinilai perlu terkait menegangnya \nhubungan kedua negara. karena itu dianggap sebagai terapi kejut alias <em>shock\n therapy</em>.</strong></p>\n<p>Terapi kejut tersebut, menurut Pengamat politik Lembaga Ilmu \nPengetahuan Indonesia (LIPI), Indira Samego, harus dilakukan pemerintah \nIndonesia sebagai bentuk peringatan kepada pemerintah Malaysia. Salah \nsatu caranya adalah bisa dengan menarik Duta Besar Indonesia untuk \nMalaysia.</p>\n<p>"Penarikan (Dubes) bisa <em>shock teraphy</em>, tapi harus ada tindak \nlanjut yang dilakukan Pemerintah Indonesia, jangan hanya panas-panas tai\n ayam karena ada kasus ini tapi harus reaktif dan terus dilakukan \ndiplomasi," tuturnya di Jakarta, Jumat (27/8).</p>\n<p>Kendati demikian, yang juga tidak kalah penting, imbuh Samego, Menlu \nMarty Natalegawa harus lebih pro aktif untuk menyelesaikan masalah. \nInsiden penangkapan tiga pegawai Dinas Kelautan dan Perikanan (DKP) \nKepulauan Riau oleh polisi Diraja Malaysia, bukan tidak mungkin akan \nterulang pada masa mendatang.</p>\n<p>"Bukan hanya karena pegawai DKP ditangkap, tetapi di masa depan akan \nmuncul persoalan baru. Artinya Menlu kita harus mengerahkan diplomat \nkita untuk bekerja, terutama di Malaysia," saran Indria.</p>\n<p>Pemerintah, lanjut dia, selama ini memang hanya menunggu. "Biasanya \nbaru bergerak setelah ada laporan," tandas dia. [jib]</p>', 'admin', '', '2010-08-28 02:31:54', 1, 8, 'http://static.inilah.com/data/berita/foto/778321.jpg', 1, 'indonesia,malaysia', ''),
(2, 'Warga Malaysia Tolak Balas Perlakuan Demonstran RI', '<p>Jakata - Masyarakat Malaysia menyayangkan aksi unjuk rasa dengan \nmelempar kotoran manusia ke kedutaan besar Malaysia. Namun, mereka \nmenolak untuk melakukan aksi balasan terhadap KBRI di Malaysia.</p>\n<p>Hal itu disampaikan oleh ketua Ikatan Setiakawan Wartawan Malaysia \ndan Indonesia (ISWMI) Datuk Ahmad Talib dalam perbincangan di Jakarta, \nJumat (27/08). Menurutnya warga dan media massa Malaysia merasa kaget \ndengan insiden tersebut, karena  selama ini orang Indonesia dikenal \ndengan kesopanan dan budi pekerti yang baik di Malaysia.</p>\n<p>"Kita melihat itu, kita bertanya mengapa sampai begitu itu, kami \nheran. Mungkin ada segelintir orang bertindak demikian. Itu tidak \nmencerminkan kesopanan, budi pekerti orang Indonesia yang kami kenal. \nKami melihat saudara di Indonesia satu rumpun yang akrab," jelas Datuk \nAhmad.</p>\n<p>Menurutnya, tidak ada aksi balasan di Malaysia, bahkan desakan agar \nmerusak Kedutaan Besar Republik Indonesia (KBRI) di sana hampir tidak \nada. "Kalaupun ada mungkin dari individu yang tidak jelas. Ada unjuk \nrasa pemuda UMNO beberapa jam, memberitahu KBRI agar menghentikan apa \nyang dilakukan di Jakarta. Mereka hanya kecewa saja."</p>\n<p>Saat ditanya apakah menurutnya media massa Indonesia terlalu \nmembesar-besarkan apa yang terjadi antar kedua negara, Datuk Ahmad Talib\n mengatakan tidak bisa menilai hal itu. "Saya tidak berani menyatakan \ndemikian. Kita semua sama-sama wartawan yang kadang-kadang membuat \nkesalahan. Kita berpatokan pada fakta." [TJ]</p>', 'admin', '', '2010-08-28 02:33:29', 1, 8, 'http://static.inilah.com/data/berita/foto/778101.jpg', 1, 'indonesia,malaysia', ''),
(3, '12 Sniper Amankan Arus Mudik &amp; Balik Jalur Pantura', '<p>Situbondo - Tekad pemerintah untuk memberikan rasa aman dan nyaman\n pada mudik dan arus mudik jelang lebaran nanti tampaknya serius \ndilakukan. Sebab, 12 sniper diterjunkan untuk mengamankan jalur di \nPantura Situbondo.</p>\n<p>12 penembak jitu itu diterjunkan langsung oleh Polres Situbondo pada \nH-7 dan H+7 lebaran. Para penembak jitu tersebut berasal dari Markas \nBrimob Kelapa Dua Cimanggis Bogor yang di BKO-kan ke Polres Situbondo.</p>\n<p>Mereka nantinya, akan ditempatkan pada dua titik rawan di Jalur \nPantura Situbondo, seperti di Hutan Baluran Desa/Kecamatan Banyuputih \nserta titik rawan di sekitar Hutan Desa, Kecamatan Banyuglugur.</p>\n<p>Maklum, dua titik rawan tersebut sering digunakan para bajing loncat \nuntuk melakukan aksinya dengan menghadang serta merampok kendaraan umum \nyang melintas. Bahkan, dalam melakukan aksinya, pelaku tidak segan-segan\n mengancam keselamatan jiwa para pengendara.</p>\n<p>"Seperti kendaraan truk yang sarat dengan muatan yang sering \ndijadikan sasaran utama oleh para kawanan bajing loncat di Jalur Pantura\n Situbondo, yang diketahui merupakan jalur pantura terpanjang di Jawa \nTimur," tutur Kapolres Situbondo, AKBP Imam Thobroni, Jumat (27/8).</p>\n<p>Menurutnya, langkah tersebut dilakukan dengan tujuan untuk memberi \nrasa aman dan nyaman bagi para pengemudi, baik pengemudi kendaraan umum \nmaupun kendaraan pribadi yang sedang melintas di Jalur Pantura \nSitubondo.</p>\n<p>Utamanya bagi para pemudik lebaran. Sebab, menjelang H-7 menjelang \nlebaran hingga H+ 7 sesudah lebaran, hampir dipastikan arus lalu lintas \ndi jalur pantura Situbondo akan makin  padat. [beritajatim.com/jib/mah]</p>', 'admin', '', '2010-08-28 02:34:29', 1, 8, 'http://static.inilah.com/data/berita/foto/778191.jpg', 1, 'arus mudik,lebaran', ''),
(4, 'Kontroversi Qory Sandioriva', '<p>Jakarta - Putri Indonesia 2009 Qory Sandioriva, 19, sudah kembali \nke Tanah Air, Kamis (26/8), setelah tiga pekan mengikuti ajang Miss \nUniverse di Las Vegas AS. Sementara tayangan video sesi wawancaranya di \najang Miss Universe AS pada situs Youtube membuat heboh, karena Qory \ntergagap-gagap menjawab pertanyaan juri.</p>\n<p>Qory tampak gagap dalam menjawab setiap pertanyaan yang dilontarkan \nkepadanya dalam bahasa Inggris. Kalimat yang terlontar dari mulutnya \ntidak beraturan, bahkan terdengar kacau. Sehingga, tidak jelas apa yang \nsebenarnya ingin ia katakan.</p>\n<p>Qory mengulang kasus yang sama yang terjadi pada Putri Indonesia \n2005, Nadine Chandrawinata ketika tampil pada ajang Miss Universe 2006.</p>\n<p>Siapakah Qory Sandioriva sebenarnya?</p>\n<p>Qory adalah seorang dara cantik kelahiran Jakarta, 17 Agustus 1991, \nyang dinobatkan sebagai Puteri Indonesia 2009 melalui penjurian yang \ndilaksanakan oleh YPI (Yayasan Puteri Indonesia).</p>\n<p>Atas kemenangan itu, ia berhak mendapatkan tropi, mobil dinas dan \nuang senilai Rp10 juta, serta beberapa hadiah dari pihak sponsor.</p>\n<p>Bapak Qory yang berasal dari Sunda dan ibunya berdarah Aceh, malah \nhampir dikatakan tidak pernah tinggal lama di Aceh, malah Qory sendiri \nmengakui sejak kecil memang ia tidak pernah memakai jilbab.</p>\n<p>Sebelumnya kisah kemenangan Qory ini sempat menjadi pemberitaan media\n massa, karena terkesan kontroversial dan dikaitkan dengan tindakan Qory\n yang dianggap melepas jilbabnya.</p>\n<p>Ia menjadi sorotan publik karena pada saat kontes berlangsung, Qory \nmemberikan jawaban kepada penguji seolah-seolah dalam kehidupan \nsehari-harinya ia memakai jilbab.</p>\n<p>Penobatan Qory Sandioriva sebagai Puteri Indonesia 2009, sekaligus \nmerupakan tiket untuk mengikuti ajang pemilihan Miss Universe 2010. Baik\n secara fisik maupun mental, Qory harus menyiapkan diri untuk tidak \nhanya disebut sebagai peserta pelengkap di ajang bergengsi dunia itu.</p>\n<p>Di lain sisi terpilihnya Qory Sandrioriva menjadi Putri Indonesia \n2009 sangat disesalkan ulama Aceh. Dia dianggap tidak mencerminkan \nsebagai putri dari daerah itu yang menerapkan syariat Islam.</p>\n<p>"Qory bukan cerminan putri Aceh. Untuk itu, ia tidak berhak \nmengatasnamakan rakyat Aceh. Ini sangat kita sesalkan," kata Sekretaris \nUlama Dayah Aceh (HUDA), Tengku Faisal Aly.</p>\n<p>"Qory boleh saja mengikuti pemilihan putri Indonesia, itu hak dia. \nTapi untuk menobatkan sebagai putri Aceh tidak bisa, karena dia tidak \nbisa menjaga sifat-sifat budaya Aceh yang Islami," ujarnya menambahkan.</p>\n<p>Menurut Antroplog Universitas Malikus Shalih Lhoksuemawe Teuku Kemal \nFasya, terpilihnya Qory tidak memiliki korelasi dengan penerapan syariat\n Islam di Aceh.</p>\n<p>Di situs YouTube sendiri memang banyak kecaman yang ditujukan kepada \nQory. Beberapa komentar terkesan mengkritik tajam. namun, dukungan \nterhadap Qory pun tetap ada. Para pendukung Indonesia juga terus \nmemberikan dukungan kepadanya.</p>\n<p>"Sebagai teman Qory, saya cukup sedih juga melihat video tersebut, \nkarena komentarnya tajam-tajam. Mungkin saja Qory agak gugup, jadinya \nseperti itu," kata Zukhriatul Hafizah, Putri Indonesia Lingkungan 2009.</p>\n<p>Pengiriman kontestan Putri Indonesia ke ajang Miss Universe yang di \nsponsori oleh YPI selama ini, memang selalu menimbulkan pro dan kontra \ndan hasilnya memang tidak pernah ada yang sempat menjadi juara atau \nminimal menjadi putri favorit dalam salah satu kategori kontes tersebut.\n [mor]</p>', 'admin', '', '2010-08-28 02:37:50', 1, 1, 'http://static.inilah.com/data/berita/foto/777141.jpg', 0, 'qory', ''),
(5, 'Patah Hati, Bobot Zee Zee Shahab Turun 3Kg?', '<p>Jakarta - Bintang sinetron Zee Zee Shahab turun berat badan 3 \nKilogram. Hal ini karena putus cinta dengan Tommy Kurniawan. Benarkah?</p>\n<p>"Kurusan dari 50kg sampai 47kg. Sebenarnya aku memang <em>nggak</em> bisa gemuk," ungkap Zee Zee ketika ditemui di Pondok Indah Mall 2, \nJakarta Selatan, Kamis (26/8).</p>\n<p>Zee membantah berat badannya turun gara-gara patah hati. "Nggak putus\n sama Tommy Kurniawan. Intinya belum jodoh saja," jelasnya.</p>\n<p>Bintang sinetron Mariam Mikrolet ini menuturkan jika ia dengan Tommy \nsebenarnya tidak pernah punya hubungan spesial.</p>\n<p>"Aku <em>nggak</em> jadian. Memang teman dekat saja. Dan, sekarang <em>nggak</em> ada yang berubah," tutupnya. [aji]</p>', 'admin', '', '2010-08-28 02:39:32', 1, 1, 'http://static.inilah.com/data/berita/foto/777461.jpg', 0, 'zee', ''),
(6, 'Jennifer Aniston Topless di Film Terbaru', '<p>Mantan aktris pemeran serial televisi <em>Friends</em> Jennifer \nAniston berencana mengambil peran paling menantang, yaitu beradegan <em>topless</em>.</p>\n<p>Ia akan membintangi film berjudul <em>Wanderlust</em> yang menuntut \ndirinya untuk telanjang untuk pertama kali selama karir aktingnya. \nKarakternya juga diikuti oleh adegan <em>threesome</em> dengan dua wanita \nlain, tidur dengan sejumlah pria dan menggunakan obat-obatan.</p>\n<p>Kabar di Hollywood mengatakan Aniston telah menyetujui untuk berperan\n sebagai seorang istri yang menganut ''seks bebas'' yang akhirnya  tinggal\n dalam sebuah komunitas hippie.</p>\n<p>Peran itu akan menjadi sebuah permulaan besar bagi aktris itu yang \nselama ini tampil dalam  seri-seri komedi romantis sejak dirinya \nberperan sebagai Rachel dalam <em>Friends</em>.</p>\n<p>Berdasarkan laporan di Hollywood, Aniston menginginkan film arahan \nsutradara Judd Apatow itu guna meningkatkan karirnya.</p>\n<p>Film terbarunya <em>The Switch</em>, dengan peran sebagai seorang gadis\n lajang yang menggunakan donor sperma agar mempunyai anak, tidak cukup \nbaik dalam perolehan <em>box office</em>.</p>\n<p>Pada <em>Wanderlust</em> Aniston memerankan tokoh Linda yang pindah \ndari komunitas hippie setelah lelah akan hidupnya di Kota New York.</p>\n<p>Dalam Naskah itu, yang telah dilihat oleh situs selebriti \nHollywoodlife.com, karakter yang diperankan Aniston akan menanggalkan \nbagian atas tubuhnya untuk menggoda para pekerja yang sedang membangun \nsupermarket.</p>\n<p>Film itu juka tampaknya akan memasukkan beberapa adegan seks, \nmisalnya, Aniston yang tidur dengan sejumlah pria pada komunitas hippie \ntempat dia tinggal  bersama suaminya, yang di perankan oleh Paul Rudd.</p>\n<p>Prospek dari penampilan <em>topless</em> Aniston akan membuat publikasi\n besar untuk film  maupun sang aktris. [*/mor]</p>', 'admin', '', '2010-08-28 02:42:01', 1, 1, 'http://static.inilah.com/data/berita/foto/777711.jpg', 1, 'jenifer aniston', ''),
(7, 'Justin Long Lecehkan Nabi Muhammad', '<p>Los Angeles - Justin Long memberikan komentar yang mengejutkan di \ndepan publik. Justin melecehkan Nabi Muhammad SAW.</p>\n<p>Justin yang juga kekasih <em>Drew Barrymore</em> ini membandingkan Mr \nDick-nya dengan ''milik'' Nabi Muhammad. Demikin mengutip <em>The Sun</em>, \nJumat (27/8).</p>\n<p>"Saya dengar Anda melakukan adegan frontal dalam film yang banyak \nmendapat sensor. Apa yang terjadi?" tanya Drew.</p>\n<p>Justin lalu bergurau menjawab, "Rupanya Mr Dick saya terlalu mencolok\n dibandingkan dengan milik Nabi Muhammad."</p>\n<p>Dia menggembor-gemborkan episode kontroversial film <em>South Park</em> pada April lalu dengan menyerang agama.</p>\n<p>Justin Jake Long yang lahir pada 2 Juni 1978 merupakan seorang aktor \nberkebangsaan Amerika Serikat yang menjadi terkenal berkat filmnya <em>Jeepers\n Creepers, Waiting..., Accepted, Dodgeball, Herbie: Fully Loaded,</em> dan <em>Live Free or Die Hard.</em> [aji/mor]</p>', 'admin', '', '2010-08-28 02:44:40', 1, 1, 'http://static.inilah.com/data/berita/foto/776021.jpg', 0, 'justin long', ''),
(8, 'Tanpa Kejutan Berarti', '<p>Monaco &ndash; Hingga babak pertama usai, Inter Milan dan Atletico \nMadrid belum bisa mencetak gol pada laga Piala Super Eropa di Stade \nLouis II, Sabtu (28/8) dini hari WIB.</p>\n<p>Seperti diketahui, Inter merupakan juara Liga Champions musim lalu \nsetelah mengalahkan Bayern Munchen, sedang Atletico jadi kampiun Europa \nLeague usai menundukkan Fulham.</p>\n<p>Pelatih Nerazzuri Rafael Benitez turun dengan skuad terbaiknya. \n4-3-2-1 adalh formasinya. Diego Milito sendiri di depan, namun dapat \ndukungan penuh dari Samuel Eto&rsquo;o dan Wesley Sneijder di belakangnya.</p>\n<p>Sama, QuiQue Flores yang menunggangi Los Rojiblancos juga \nbermaterikan pemain terbaiknya dengan pola 4-1-3-2. Forlan dan Aguerro \njadi duet maut di depan.</p>\n<p>Laga berlangsung cukup keras namun bertempo sedang. Ketatnya <em>pressing</em> kedua kubu membuat jalannya babak pertama minim peluang.</p>', 'admin', '', '2010-08-28 02:46:23', 1, 4, '', 3, 'olah raga', ''),
(9, 'Ibrahimovic ''Hamil Delapan Bulan''', '<p>Barcelona &ndash; Zlatan Ibrahimovic ''hamil delapan bulan''. Tinggal \nmenghitung waktu, dia akan bergabung dengan AC Milan.</p>\n<p>Agen Ibrahimovic, Mino Raiola, menggunakan metafora itu untuk \nmenggambarkan kondisi negosiasi pihaknya dengan Wakil Presiden AC Milan. ''Bayinya hampir lahir,'' katanya.</p>\n<p>Pertemuan Raiola dengan Galliani berlangsung di Hotel Rey Juan Carlos\n I di Barcelona. Hasilnya, sejauh ini, sangat positif.</p>\n<p>''Bayi itu memang belum lahir. Tapi, katakanlah kami sekadang sedang \n(hamil) delapan bulan,'' ujar Raiola tertawa.</p>\n<p>Menurutnya, pertemuan dengan Galliani sangat menyenangkan. Banyak \nhasil positif yang sudah didapatkan. Mereka membicarakan banyak hal.</p>\n<p>"Kesepakatan dengan Milan belum final. Kami belum berada pada bagian \nyang panas dari negosiasi ini. Pendeknya, karena itu memang tak \ndibutuhkan," ujarnya.</p>\n<p>Ditambahkannya, saat ini masih banyak hal yang harus dibereskan di \nantara mereka. Kecuali itu, juga harus dibereskan di antara kedua klub, \nAC Milan dan Barcelona.</p>\n<p>"Bahkan jika Ibra menyatakan iya secara langsung, dia belum akan \nberada di Milan besok," tuturnya.</p>\n<p>Tetapi, sejumlah media di Italia percaya, kesepakatan itu praktis \nsudah tercapai. Dan, <em>I Rossoneri</em> akhirnya harus menemukan dana \nsegar untuk mengontrak mantan striker Inter Milan itu. [ing]</p>', 'admin', '', '2010-08-28 02:49:23', 1, 4, '', 7, 'olah raga', '');

-- --------------------------------------------------------

--
-- Table structure for table `bukutamu`
--

CREATE TABLE IF NOT EXISTS `bukutamu` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `sekarang` varchar(12) DEFAULT NULL,
  `nama` varchar(50) DEFAULT NULL,
  `email` varchar(60) DEFAULT NULL,
  `homepage` varchar(60) DEFAULT NULL,
  `alamat` varchar(100) DEFAULT NULL,
  `komentar` text,
  `jawab` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bukutamu`
--

INSERT INTO `bukutamu` (`id`, `sekarang`, `nama`, `email`, `homepage`, `alamat`, `komentar`, `jawab`) VALUES
(1, '27-Aug-2010', 'Iwan', 'admin@auracms.org', 'http://iwan.or.id', 'Purwokerto', 'Tes Bukutamu AuraCMS 2.3 Oke lah ya heheh :)', '');

-- --------------------------------------------------------

--
-- Table structure for table `bukutamu_config`
--

CREATE TABLE IF NOT EXISTS `bukutamu_config` (
  `config` text NOT NULL,
  `id` int(6) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `bukutamu_config`
--

INSERT INTO `bukutamu_config` (`config`, `id`) VALUES
('a:2:{s:5:"limit";i:15;s:4:"char";i:500;}', 1);

-- --------------------------------------------------------

--
-- Table structure for table `halaman`
--

CREATE TABLE IF NOT EXISTS `halaman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(80) NOT NULL DEFAULT '',
  `konten` text NOT NULL,
  `seftitle` varchar(225) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `halaman`
--

INSERT INTO `halaman` (`id`, `judul`, `konten`, `seftitle`) VALUES
(1, ' ', '<p style="text-align: center;"><a title="Infolinks.Com" href="http://www.infolinks.com/opt-out.html?pid=37209&amp;wsid=1" target="_blank"><img title="inilah.com" src="http://static.inilah.com/data/berita/thumbnail/592841.jpg" alt="Kartunis Ariel Peterpan" width="308" height="228" /></a></p>', ''),
(2, 'Sejarah AuraCMS', '<p align="justify">Awal mulanya, AuraCMS itu berasal dari ide yang tercetus saat ingin membuat website dengan konten dinamis.</p>\r\n<p align="justify">Pada saat itu muncul ide untuk membuat kumpulan script PHP yang terintegrasi. Dan kemudian terbuatlah dua buah jenis script PHP tersebut yang satu menggunakan data berupa file text dan yang lainnya menggunakan database MySQL.</p>\r\n<p align="justify">Kemudian setelah dicoba ternyata data yang menggunakan database MySQL jauh lebih gampang dan tidak rumit dalam pengelolaannya sehingga yang menggunakan data berupa file text tidak dilanjutkan lagi. Setelah itu script yang menggunakan database MySQL tadi diberi nama <strong>"aura"</strong> dan karena merupakan software Content Management System maka nama lengkapnya <strong>"AuraCMS"</strong>.</p>\r\n<p align="justify">Bekerjasama dengan <a href="http://kioss.com/" target="_blank"><strong>Kioss Project</strong></a> versi pertama diluncurkan pada pertengahan tahun 2003. Kemudian disusul dengan versi versi berikutnya dan sempat "mandeg" beberapa waktu pada versi 1.3&amp;1.4. <br />Dan pada saat versi 1.5 terbit mulai banyak yang menyumbang modul ataupun modifikasi dan ide-ide baru sehingga terbit versi 1.6 beta sebagai versi percobaan.<br />Ternyata dilaporkan bahwa pada versi 1.6 beta ini masih ada beberapa bug sehingga pada bulan Juli 2005 diterbitkan versi baru yang tidak beta lagi yaitu versi 1.61 dengan mengeliminasi bug pada versi 1.6 beta dan menambahkan beberapa fitur yang baru.</p>', ''),
(4, 'Tentang AuraCMS', '<p align="justify"><strong>AuraCMS</strong> adalah hasil karya anak bangsa yang merupakan software CMS (Content Managemen System) untuk website yang berbasis PHP4 &amp; MySQL berlisensi GPL (General Public License).</p>\r\n<p align="justify">Dengan bentuk yang sederhana dan mudah ini diharapkan dapat digunakan oleh pemakai yang masih pemula sekalipun.</p>\r\n<p align="justify">Dan tak lupa bahwa software ini mungkin tak semuanya memenuhi harapan pemakai, oleh karena itu diharapkan adanya kritikan, sumbangan pikiran atau mungkin bentuk modifikasi dari para pengguna sekalian baik berupa modul maupun perubahan-perubahan lainnya yang dapat menjadikan <strong>auraCMS</strong> ini menjadi lebih baik.</p>\r\n<p align="justify">Terimakasih.</p>', ''),
(3, 'Site Credit', '<p>Terima kasih kepada:</p>\r\n<ul>\r\n<li>Allah SWT, yang telah meciptakan alam semesta beserta isinya. <br />Kioss Project yang telah memberikan dukungan dan bantuan hosting bagi auraCMS. </li>\r\n<li>Komunitas Opensource Indonesia yang telah mempopulerkan auraCMS. </li>\r\n<li>Benruth Consultancy untuk script php instantfeedreader yang digunakan untuk membaca rss/xml dari kode javascript. </li>\r\n<li>Iwan Web Design atas ide-ide, modifikasi auraCMS beserta modul-modul yang disumbangkan.</li>\r\n<li>Iwan Website atas ide-ide, modifikasi auraCMS beserta modul-modul yang disumbangkan. </li>\r\n<li>Portal Iseng - Priyatmoko atas ide-ide, modifikasi auraCMS beserta modul-modul yang disumbangkan. </li>\r\n<li>Iman Hermawan atas ide-ide, modifikasi auraCMS beserta modul-modul yang disumbangkan. </li>\r\n<li>Agus Wahyudi atas kesediannya meyediakan hosting dan domain <a href="http://auracms.org">http://auracms.org</a> </li>\r\n<li>Para pengguna auraCMS yang mau memberikan perhatian terhadap auraCMS </li>\r\n<li>Para pengunjung atas kesediaannya untuk meluangkan waktu mengunjungi situs ini. </li>\r\n<li>Dan semuanya yang tidak dapat disebutkan satu-persatu. </li>\r\n</ul>\r\n<p>This site is powered by :</p>\r\n<ul>\r\n<li>Apache </li>\r\n<li>P H P </li>\r\n<li>MySQL </li>\r\n<li>Linux OS </li>\r\n<li>CPanel </li>\r\n<li>auraCMS</li>\r\n</ul>', ''),
(5, 'Donasi', '<p style="text-align: justify;">Jika anda menyukai software ini dan ingin menyumbangkan dana, anda boleh menyumbangkan dana seberapapun kepada kami, kami akan sangat menghargai sumbangan Anda.</p>\r\n<p>Donasi bisa ditransfer ke rekening kami di:</p>\r\n<p><strong><span style="color: #0000ff;">BNI Cabang Purwokerto</span><br />No. Rek. 0151742075<br />a.n. Iwan Susyanto<br /><br /></strong><strong><span style="color: #0000ff;">BNI Cabang Cimahi</span><br />No. Rek.0100257099<br />a.n. Rumi Ridwan Sholeh<br /></strong><strong><br /></strong></p>\r\n<p>Terima kasih atas donasinya, semoga Allah SWT memberikan balasan yang lebih baik kepada Anda.</p>', ''),
(6, 'Promo Hosting', '<div class="std-table">\r\n<table class="wide" border="0" cellspacing="0" cellpadding="0">\r\n<tbody>\r\n<tr>\r\n<th rowspan="2"><span style="font-size: x-small;"><span style="font-family: Verdana;">FITUR HOSTING<br /></span></span></th> <th colspan="4"><span style="font-family: Verdana; font-size: x-small;"> PAKET HOSTING<br /></span></th>\r\n</tr>\r\n<tr>\r\n<th><span style="font-family: Verdana; font-size: x-small;">I</span></th> <th> <span style="font-family: Verdana; font-size: x-small;">II</span></th> <th> <span style="font-family: Verdana; font-size: x-small;">III</span></th> <th> <span style="font-family: Verdana; font-size: x-small;">IV</span></th>\r\n</tr>\r\n<tr style="background-color: #FFFFFF;" onmouseover="this.style.backgroundColor=''#99CC00''" onmouseout="this.style.backgroundColor=''#FFFFFF''">\r\n<td><span style="font-family: Verdana; font-size: x-small;">Disk Space</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;"> 100 MB</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">200 MB</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">500 MB</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">1 GB</span></td>\r\n</tr>\r\n<tr style="background-color: #e6edc2;" onmouseover="this.style.backgroundColor=''#99CC00''" onmouseout="this.style.backgroundColor=''#e6edc2''">\r\n<td><span style="font-family: Verdana; font-size: x-small;">Bandwith</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;"> Unlimited </span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">Unlimited </span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">Unlimited</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">Unlimited</span></td>\r\n</tr>\r\n<tr style="background-color: #FFFFFF;" onmouseover="this.style.backgroundColor=''#99CC00''" onmouseout="this.style.backgroundColor=''#FFFFFF''">\r\n<td><span style="font-family: Verdana; font-size: x-small;">Kontrak Minimal</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;"> 1Tahun</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">1 Tahun</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">1 Tahun</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">1 Tahun</span></td>\r\n</tr>\r\n<tr style="background-color: #e6edc2;" onmouseover="this.style.backgroundColor=''#99CC00''" onmouseout="this.style.backgroundColor=''#e6edc2''">\r\n<td><span style="font-family: Verdana; font-size: x-small;">MySQL DB<br /></span></td>\r\n<td class="c" style="text-align: center"><span style="font-size: x-small;"><span style="font-family: Verdana;">Unlimited</span></span></td>\r\n<td class="c" style="text-align: center"><span style="font-size: x-small;"><span style="font-family: Verdana;">Unlimited</span></span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">Unlimited</span></td>\r\n<td class="c" style="text-align: center"><span style="font-size: x-small;"><span style="font-family: Verdana;">Unlimited</span></span></td>\r\n</tr>\r\n<tr style="background-color: #FFFFFF;" onmouseover="this.style.backgroundColor=''#99CC00''" onmouseout="this.style.backgroundColor=''#FFFFFF''">\r\n<td><span style="font-family: Verdana; font-size: x-small;">Addon Domain<br /></span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;"> 2</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">3</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">4</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">8</span></td>\r\n</tr>\r\n<tr style="background-color: #e6edc2;" onmouseover="this.style.backgroundColor=''#99CC00''" onmouseout="this.style.backgroundColor=''#e6edc2''">\r\n<td><span style="font-family: Verdana; font-size: x-small;">Biaya 12 Bulan (Best Price)<br /></span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">&nbsp; 180.000</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">225.000</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">350.000</span></td>\r\n<td class="c" style="text-align: center"><span style="font-family: Verdana; font-size: x-small;">600.000</span></td>\r\n</tr>\r\n<tr class="even">\r\n<td>&nbsp;</td>\r\n<td class="c" style="text-align: center"><a href="http://duniamaya.web.id/home/order" target="_blank"><img style="vertical-align: middle;" title="Dedicated Server Indonesia Murah" src="images/order.gif" alt="Dedicated Server Indonesia Murah" width="47" height="20" /></a></td>\r\n<td class="c" style="text-align: center"><a href="http://duniamaya.web.id/home/order" target="_blank"><img style="vertical-align: middle;" title="Dedicated Server Indonesia Murah" src="images/order.gif" alt="Dedicated Server Indonesia Murah" width="47" height="20" /></a></td>\r\n<td class="c" style="text-align: center"><a href="http://duniamaya.web.id/home/order" target="_blank"><img style="vertical-align: middle;" title="Dedicated Server Indonesia Murah" src="images/order.gif" alt="Dedicated Server Indonesia Murah" width="47" height="20" /></a></td>\r\n<td class="c" style="text-align: center"><a href="http://duniamaya.web.id/home/order" target="_blank"><img style="vertical-align: middle;" title="Dedicated Server Indonesia Murah" src="images/order.gif" alt="Dedicated Server Indonesia Murah" width="47" height="20" /></a></td>\r\n</tr>\r\n</tbody>\r\n</table>\r\n</div>\r\n<ul>\r\n</ul>', ''),
(7, 'Keuntungan', '<p>Bagi Member Duniamaya akan mendapatkan beberapa di antaranya :</p>\r\n<ol>\r\n<li>AuraCMS ver 2.3 beta<br />Website mod rewrite seperti yang di AuraCMS saat ini sehingga akan mudah terindek oleh google<br /></li>\r\n<li>Bandwith Unlimited</li>\r\n</ol>\r\n<p>Oke segeralah Daftar Hosting di Duniamaya.</p>\r\n<p>Salam Hangat</p>\r\n<p>&nbsp;</p>\r\n<p>Iwan Susyanto, S.Si</p>\r\n<p>081 327 575 145</p>\r\n<p>&nbsp;</p>', '');

-- --------------------------------------------------------

--
-- Table structure for table `intrusions`
--

CREATE TABLE IF NOT EXISTS `intrusions` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) NOT NULL DEFAULT '',
  `value` text NOT NULL,
  `page` varchar(255) NOT NULL DEFAULT '',
  `ip` varchar(15) NOT NULL DEFAULT '',
  `impact` int(11) unsigned NOT NULL DEFAULT '0',
  `created` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `intrusions`
--

INSERT INTO `intrusions` (`id`, `name`, `value`, `page`, `ip`, `impact`, `created`) VALUES
(1, 'judul', 'ibrahimovic-hamil-delapan-bulan-3-6-2', '/auracms2.3/article-9-ibrahimovic-hamil-delapan-bulan-3-6-2.html', 'local/unknown', 7, '2010-08-28 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `komentar`
--

CREATE TABLE IF NOT EXISTS `komentar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(90) NOT NULL DEFAULT '',
  `konten` text NOT NULL,
  `user` varchar(30) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `tgl` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `artikel` int(11) NOT NULL DEFAULT '0',
  `ip` varchar(40) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `komentar`
--


-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `menu` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(127) NOT NULL DEFAULT '',
  `published` int(1) NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `menu`, `url`, `published`, `ordering`) VALUES
(1, 'Beranda', 'index.php', 1, 1),
(2, 'Interaktif', '#', 1, 2),
(3, 'Hosting IIX Support AuraCMS', 'http://duniamaya.web.id', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `menu_users`
--

CREATE TABLE IF NOT EXISTS `menu_users` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `menu` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  `ordering` int(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `menu_users`
--

INSERT INTO `menu_users` (`id`, `menu`, `url`, `ordering`) VALUES
(1, 'Logout', 'index.php?aksi=logout', 4),
(2, 'Change Password', 'index.php?pilih=user&amp;aksi=change', 3),
(3, 'Send Article', 'admin.php?pilih=news&amp;mod=yes', 1),
(4, 'Send Lyrics', 'admin.php?pilih=lyrics&amp;mod=yes', 2);

-- --------------------------------------------------------

--
-- Table structure for table `modul`
--

CREATE TABLE IF NOT EXISTS `modul` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `modul` varchar(30) NOT NULL DEFAULT '',
  `isi` text NOT NULL,
  `setup` varchar(50) NOT NULL DEFAULT '',
  `posisi` tinyint(2) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '0',
  `ordering` int(5) NOT NULL DEFAULT '0',
  `type` enum('block','module') NOT NULL DEFAULT 'module',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `modul`
--

INSERT INTO `modul` (`id`, `modul`, `isi`, `setup`, `posisi`, `published`, `ordering`, `type`) VALUES
(1, 'Artikel Terakhir', 'mod/artikel_terakhir/terakhir.php', '', 0, 0, 1, 'module'),
(2, 'Statistik Situs', 'mod/statistik/stat.php', '', 0, 1, 4, 'module'),
(3, 'Polling', 'mod/polling/polling.php', '', 1, 0, 3, 'module'),
(4, 'Kalender', 'mod/calendar/calendar.php', '', 1, 0, 8, 'module'),
(5, 'Pesan Singkat', 'mod/shoutbox/shoutboxview.php', '', 1, 1, 5, 'module'),
(6, 'Random Links', 'mod/random_link/randomlink.php', '', 0, 1, 2, 'module'),
(7, 'Top Download', 'mod/top_download/topdl.php', '', 1, 0, 10, 'module'),
(8, 'Login', 'mod/login/login.php', '', 1, 1, 3, 'module'),
(9, 'Hijjrah Date', 'mod/mod_hdate_ge/mod_hdate_ge.php', '', 0, 0, 5, 'module'),
(10, 'ip logs', 'mod/phpids/ids.php', '', 0, 1, 3, 'module'),
(11, 'Support', '<div align="center">\r\nIwan<br />\r\n<a href="ymsgr:sendIM?iwansusyanto"><img src="http://opi.yahoo.com/online?u=iwansusyanto&amp;m=g&amp;t=1&amp;l=us&amp;opi.jpg" border="0" alt="Status YM" /></a><br />Arif Supriyanto<br />\r\n<a href="ymsgr:sendIM?disemarang"><img src="http://opi.yahoo.com/online?u=disemarang&amp;m=g&amp;t=1&amp;l=us&amp;opi.jpg" border="0" alt="Status YM" /></a>\r\n<br />Ridwan<br />\r\n<a href="ymsgr:sendIM?floodbost"><img src="http://opi.yahoo.com/online?u=floodbost&amp;m=g&amp;t=1&amp;l=us&amp;opi.jpg" border="0" alt="Status YM" /></a>\r\n<br />\r\nRatno<br />\r\n<a href="ymsgr:sendIM?slendroo"><img src="http://opi.yahoo.com/online?u=slendroo&amp;m=g&amp;t=1&amp;l=us&amp;opi.jpg" border="0" alt="Status YM" /></a><br />AgusW<br />\r\n<a href="ymsgr:sendIM?aguzw"><img src="http://opi.yahoo.com/online?u=floodbost&amp;m=g&amp;t=1&amp;l=us&amp;opi.jpg" border="0" alt="Status YM" /></a>\r\n\r\n</div>', '', 1, 1, 9, 'block'),
(13, 'Penghasil Uang', '<div style="text-align:center;">\r\n<script type="text/javascript"><!--\r\n/* <![CDATA[ */\r\n\r\n\r\n\r\nfunction affiliateLink(str){ str = unescape(str); var r = ''''; for(var i = 0; i < str.length; i++) r += String.fromCharCode(6^str.charCodeAt(i)); document.write(r); }\r\n\r\n\r\n\r\naffiliateLink(''%3Ag%26ntc%60%3B%24nrrv%3C%29%29qqq%28rc%7Er+johm+gbu%28eik%299tc%60%3B%3E300%3F%248%3Aoka%26ute%3B%24nrrv%3C%29%29qqq%28rc%7Er+johm+gbu%28eik%29okgacu%29dghhctu%29%60scj+743%7E743%28lva%24%26ditbct%3B%246%24%26gjr%3B%24Rc%7Er%26Johm%26Gbu%24%298%3A%29g8'');\r\n\r\n\r\n\r\n/* ]]> */\r\n// --></script>\r\n<p><a href="http://www.ask2link.com/refer/auracms"><img src="http://ask2link.com/img/paid-to-paypal.gif" border="0" alt="Earn money from your website/blog by, selling text links, banner ads - Advertisers can, buy links, from your blog for SEO. Get paid through PayPal" /></a></p>\r\n<p><a href="http://www.neobux.com/?r=rzareza"><img src="http://images.neobux.com/imagens/banner3.gif" border="0" alt="" width="125" height="125" /></a></p>\r\n</div>', '', 1, 1, 5, 'block'),
(16, 'Advertisement', '<div style="text-align:center;"><!-- Begin: KlikSaya.com -->\r\n<script src="http://scr.kliksaya.com/js-ad.php?zid=3704" type="text/javascript">\r\n</script>\r\n<!-- End: KlikSaya.com --></div>', '', 1, 1, 7, 'block'),
(15, 'Donation', '<center><form action="https://www.paypal.com/cgi-bin/webscr" method="post">\r\n<input type="hidden" name="cmd" value="_s-xclick">\r\n<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHXwYJKoZIhvcNAQcEoIIHUDCCB0wCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYBk0beq83EaSHGF0bHdsA8Ippck6V6kdD1GO8kHdgON9LJ4ip42ugYG8xyeo/2Nq10yqmDSnBXTzbcElwtBAozS0VHoe/AEuhbuJJmv1NfxbUHfawpuM2YX+slfcAY9l2g1dhtj3ZajsYB1whnu34PSwI1xeKvgXekmLDffOLHm7TELMAkGBSsOAwIaBQAwgdwGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQISTMhEOGkOYiAgbikizWlqqi5Rk/Hb5fc9qhr9RmnwycWL2bxcX9odQhbIYkBT/XQIB6MVqq0AtzqWVNie9iMKemj/IVzFX6XQkB3PP8ffdZtNo35D1JSiZ68tqMlbkHrIEzdxloQczC3STAhy5RvyKbxzQ+IUs+5ELer3IaCFRwRCwflBZENjZ8zubWGvOgRJlW3ZHFAyIF+D8g790IA/pqMj0ZXMtMn9TPK+CfKi5gacnVGemNh2gAO+5glw5vD/rlloIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMTAwNTI2MTY0NjMyWjAjBgkqhkiG9w0BCQQxFgQUlEM+kebw3oLoxD7mMWJ7dmkL9wkwDQYJKoZIhvcNAQEBBQAEgYBqke/g5Emmp6Lx7twTCd7ifmiOLsGxNRodGSXIfpT3NaizSYpLtqo5TqTbaDvtCdhnB2pfIa5qpT7dg5vzddMC2nRPHv9QmlIbkQ3BWXWFRD+gRkhTDCzTFCI/u3N8gVEtwtxfCnE86Ld1Z9b/xrJaINV1G4LfLpQ8ZE06AAAlbQ==-----END PKCS7-----\r\n">\r\n<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">\r\n<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">\r\n</form></center>\r\n', '', 1, 1, 1, 'block');

-- --------------------------------------------------------

--
-- Table structure for table `mod_cat_download`
--

CREATE TABLE IF NOT EXISTS `mod_cat_download` (
  `kategori` varchar(30) NOT NULL DEFAULT '',
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `kid` int(12) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`kid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `mod_cat_download`
--

INSERT INTO `mod_cat_download` (`kategori`, `keterangan`, `kid`) VALUES
('AuraCMS', 'Berisi file-file AuraCMS baik rilis maupun Update', 1),
('Modul AuraCMS', 'Berisi Modul-modul AuraCMS', 2),
('Blok AuraCMS', 'Berisi script atau plugin blok AuraCMS', 3),
('Themes AuraCMS', 'Berisi themes-themes AuraCMS', 4),
('Update', 'Berisi Update dari bugs yang terjadi pada auracms', 5),
('Themes AuraCMS 2.2.2', 'Themes ini hanya berjalan di AuraCMS 2.2.2 di versi di bawahnya harus di edit lagi OK', 6);

-- --------------------------------------------------------

--
-- Table structure for table `mod_cat_link`
--

CREATE TABLE IF NOT EXISTS `mod_cat_link` (
  `kategori` varchar(30) NOT NULL DEFAULT '',
  `keterangan` varchar(255) NOT NULL DEFAULT '',
  `kid` int(12) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`kid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `mod_cat_link`
--

INSERT INTO `mod_cat_link` (`kategori`, `keterangan`, `kid`) VALUES
('Pemerintah', 'Berisi website pemerintah yang memakai AuraCMS maupun yang tidak pakai', 1),
('Personal Web', 'Berisi website pribadi yang menggunakan AuraCMS', 2),
('Pendidikan', 'Berisi Webesite sekolahan baik setingakat SMU, SMP, SD maupun Universitas', 3),
('Lain-lain', 'Berisi Website lain-lain ya hehe :)', 4),
('Links Exchange ', 'Links Exchange ', 5);

-- --------------------------------------------------------

--
-- Table structure for table `mod_download`
--

CREATE TABLE IF NOT EXISTS `mod_download` (
  `judul` varchar(255) NOT NULL DEFAULT '',
  `keterangan` text NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `hit` int(11) NOT NULL DEFAULT '0',
  `date` varchar(78) NOT NULL DEFAULT '',
  `size` varchar(20) NOT NULL DEFAULT '1',
  `kid` int(12) NOT NULL DEFAULT '0',
  `broken` int(12) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `mod_download`
--

INSERT INTO `mod_download` (`judul`, `keterangan`, `url`, `hit`, `date`, `size`, `kid`, `broken`, `id`) VALUES
('AuraCMS 2.2 Ajax', 'AuraCMS 2.2 Ajax', 'http://iwan.or.id/download/lihat/1.html', 20779, '1196686917', '200 Kb', 3, 0, 1),
('Update Shoutbox', 'Ini update untuk AuraCMS 2.2 yang yang terdapat error pada pengisian form dimana formnya tidak berjalan di Browser selain IE.', 'http://auracms.org/files/update.shoutboxAuraCMS2.2. zip', 4719, '1201695438', '3 Kb', 5, 0, 2),
('Update Gallery', 'Update Gallery untuk AuraCMS Versi 2.2', 'http://auracms.org/files/update.galleryAuraCMS2.2.zip', 3366, '1201695546', '4 Kb', 5, 0, 3),
('Modul RSS for AuraCMS 2.2', 'Modul Untuk membca RSS', 'http://auracms.org/files/mod_rss_reader_auraCMS2.2.zip', 3649, '1201787865', '10 Kb', 2, 0, 4),
('Image Alquran Online', 'Ini adalah images untuk AuraCMS Quran, silahkan di download untuk menampilkan tulisan arab dalam bentuk images. Oke. Kalau Modulnya nanti akan saya publis sebentar lagi :)', '/images/images_alquran.zip', 2666, '1203763975', '8.233 Kb', 2, 0, 5),
('Update Shoutbox &amp; Page Manager', 'Updatetan Shoutbox dan Page Manager Terbaru', 'files/updateShoutboxAndPageauraCMS2.2.zip', 2533, '1204068732', '10 Kb', 5, 0, 6),
('jam analog flash', 'blok untuk jam analog berbasis flash', 'files/clock.zip', 3119, '1204311321', '29 Kb', 3, 0, 7),
('update admin guestbook', 'update guestbook admin.', 'files/update_mod_guestbook_admin.zip', 2357, '1204315774', '6 Kb', 5, 0, 8),
('Modul Quran', 'Ini adalah modul Alquran Online, untuk database dan image nya sengaja saya pisah jadi nanti di download masing-masing ya :)', 'files/mod_quran.zip', 2611, '1204432984', '2.2 Kb', 2, 0, 9),
('Blok Tanggal Hijrah', 'Ini blok untuk menampilkan tanggalan hijrah', 'files/hijrah_date.tar.gz', 2151, '1204433122', '1.3 Kb', 3, 0, 11),
('update pageManager 2.2.1', 'update page manager 2.2.1', 'files/update_admin_pages2.2.1.zip', 2178, '1208880062', '3 Kb', 5, 0, 21),
('Blok Jadwal Sholat', 'Blok Jadwal Sholat', 'files/sholat.tar.gz', 2484, '1204433286', '8.0 Kb', 3, 0, 14),
('database alquran', 'Ini database alquran.', 'files/quran_id.sql.gz', 2896, '1206873738', '600 Kb', 2, 0, 20),
('Modul lyrics untuk auracms2.2.1', 'Modul untuk koleksi lyrics. hanya untuk versi 2.2.1', 'files/mod_lyrics_2.2.1.zip', 1547, '1206549650', '10 Kb', 2, 0, 19),
('Kirman  Themes', 'This theme is dedicated for auracms Devolvement.\r\nFor AuraCMS 2.2.1\r\n', 'http://kirman.zzl.org/dl_jump.php?id=3', 3657, '1204626585', '283 Kb', 4, 0, 15),
('Themes Beauty', 'Themes For AuraCMS 2.2.1', 'http://www.orangganteng.com/downloads.php?cat_id=2&amp;download_id=1', 10260, '1204626726', '200 Kb', 4, 0, 16),
('Themes Simple', 'Themes For AuraCMS 2.2.1', 'http://www.orangganteng.com/downloads.php?cat_id=2&download_id=2', 7347, '1204626765', '200 Kb', 4, 0, 17),
('Module Alumni', 'Modul alumni untuk auracms2.2. menampilkan data alumni. \r\n"update table struktur"', 'files/mod_alumni_auracms2.2.zip', 4121, '1205002043', '28 Kb', 2, 0, 18),
('phpids for auraCMS', 'Modul PHPids untuk auraCMS. atau IP logs', 'files/mod_phpids.zip', 2728, '1209187152', '54 Kb', 2, 0, 22),
('AuraCMS 2.2.2', 'Ada beberapa perubahan yang terjadi pada AuraCMS 2.2.2 ini diantaranya adalah sistem templatenya yang berbeda dengan AuraCMS versi-versi sebelumnya.', '/latest.php', 24545, '1211826248', '891 Kb', 1, 0, 23),
('Modul lyrics untuk auracms2.2.2', 'Modul untuk koleksi lyrics. perbedaannya hanya pada sistem menu di user.', 'files/mod_lyrics_2.2.2.zip', 2437, '1212347907', '15 Kb', 2, 0, 24),
('Update pages_data.php', 'Update file pages_data.php yang berada pada folder js/pages', '/files/update_pages_data_php.tar.gz', 2076, '1215778957', '200 Kb', 5, 0, 26),
('HigherGround Theme', 'Theme ini hanya berjalan pada AuraCMS v2.2.2 AJAX', 'files/higherground.zip', 8369, '1213268991', '174.3 Kb', 6, 0, 25),
('Modul Forum auraCMS 2.2.2', 'Modul Forum untuk auracms 2.2.2\r\nsilahkan download', 'files/mod_forum2008-11-25_063517.tar.gz', 3276, '1227569916', '60 Kb', 2, 0, 27),
('update loading merah pada hosting', 'update loading merah.. yang terus menerus.. dan tidak ada data.\r\nupdate disini. jangan lupa baca readme nya. ::update December 24, 2008 04:13:17 PM ::', 'files/update.auraCMS2.2.2-20081224.zip', 1704, '1230057244', '46 Kb', 5, 0, 28),
('modul arsip dan tags', 'modul arsip dan tags seperti yg ada di auracms. \r\nuntuk versi 2.2.2\r\n::new update::', 'files/mod_arsip_tags.auracms2.2.2.zip', 2289, '1230306996', '5 Kb', 2, 0, 29),
('file sql untuk forum', 'ini bagi yang gagal instalasi forum..\nsilahkan download dan import file forum.sql', '/files/forum.sql', 1836, '1236071561', '10 kb', 2, 0, 30),
('update news list topic auracms2.2.2', 'update untuk list topic dan paging untuk komentar.', 'files/update.news.list.topic.2.2.2.zip', 943, '1246155425', '14 Kb', 5, 0, 31),
('Menu Level 1.0', 'menu level untuk auracms 2.2.2', '/files/menulevel1.0.zip', 1011, '1258157868', '100 Kb', 2, 0, 32),
('Pooling dengan PHPPlot', 'Polling dengan phpplot bisa pie chart dan lain2', '/files/polling02.tar', 535, '1268152041', '59 Kb', 2, 0, 33);

-- --------------------------------------------------------

--
-- Table structure for table `mod_download_ratings`
--

CREATE TABLE IF NOT EXISTS `mod_download_ratings` (
  `id` varchar(11) NOT NULL DEFAULT '',
  `total_votes` int(11) NOT NULL DEFAULT '0',
  `total_value` int(11) NOT NULL DEFAULT '0',
  `used_ips` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_download_ratings`
--

INSERT INTO `mod_download_ratings` (`id`, `total_votes`, `total_value`, `used_ips`) VALUES
('31', 0, 0, ''),
('28', 0, 0, ''),
('26', 0, 0, ''),
('21', 0, 0, ''),
('8', 0, 0, ''),
('6', 0, 0, ''),
('3', 0, 0, ''),
('2', 0, 0, ''),
('25', 0, 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `mod_gallery`
--

CREATE TABLE IF NOT EXISTS `mod_gallery` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `width` int(12) NOT NULL DEFAULT '0',
  `height` int(12) NOT NULL DEFAULT '0',
  `modified` int(16) NOT NULL DEFAULT '0',
  `size` int(16) NOT NULL DEFAULT '0',
  `kid` int(12) NOT NULL DEFAULT '0',
  `users` varchar(100) NOT NULL DEFAULT '',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `view` int(32) NOT NULL DEFAULT '0',
  `id` int(12) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mod_gallery`
--

INSERT INTO `mod_gallery` (`name`, `width`, `height`, `modified`, `size`, `kid`, `users`, `desc`, `view`, `id`) VALUES
('20071124075550@ridwan.jpg', 400, 300, 1195908950, 18359, 1, '', '', 308, 1),
('20071124080305@iwan.jpg', 332, 350, 1195909385, 12403, 1, '', '', 475, 2),
('20071205081747@agus.jpg', 400, 300, 1196860667, 15114, 1, '', '', 374, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mod_gallery_kat`
--

CREATE TABLE IF NOT EXISTS `mod_gallery_kat` (
  `name` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `total` int(12) NOT NULL DEFAULT '0',
  `id` int(12) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `mod_gallery_kat`
--

INSERT INTO `mod_gallery_kat` (`name`, `desc`, `total`, `id`) VALUES
('Admin AuraCMS', 'Berisi Photo² Admin AuraCMS yang keren-keren abis', 3, 1),
('pemandangan alam', 'pemandangan alam', 0, 2),
('Naruto', 'kumpulan gallery naruto', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `mod_link`
--

CREATE TABLE IF NOT EXISTS `mod_link` (
  `judul` varchar(255) NOT NULL DEFAULT '',
  `keterangan` text NOT NULL,
  `url` varchar(255) NOT NULL DEFAULT '',
  `hit` int(11) NOT NULL DEFAULT '0',
  `date` varchar(78) NOT NULL DEFAULT '',
  `broken` int(12) NOT NULL DEFAULT '0',
  `public` int(1) NOT NULL DEFAULT '0',
  `kid` int(12) NOT NULL DEFAULT '0',
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url` (`url`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `mod_link`
--

INSERT INTO `mod_link` (`judul`, `keterangan`, `url`, `hit`, `date`, `broken`, `public`, `kid`, `id`) VALUES
('Freeware &amp; Shareware', 'Website Download Software &amp; Freeware dengan gartis', 'http://iwan.or.id', 0, '1282923610', 0, 0, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mod_news_ratings`
--

CREATE TABLE IF NOT EXISTS `mod_news_ratings` (
  `id` varchar(11) NOT NULL DEFAULT '',
  `total_votes` int(11) NOT NULL DEFAULT '0',
  `total_value` int(11) NOT NULL DEFAULT '0',
  `used_ips` longtext,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `mod_news_ratings`
--


-- --------------------------------------------------------

--
-- Table structure for table `polling`
--

CREATE TABLE IF NOT EXISTS `polling` (
  `pid` int(11) NOT NULL AUTO_INCREMENT,
  `pjudul` varchar(255) NOT NULL DEFAULT '',
  `ppilihan` varchar(255) NOT NULL DEFAULT '',
  `pjawaban` varchar(255) NOT NULL DEFAULT '',
  `created` varchar(90) NOT NULL DEFAULT '',
  `public` varchar(6) NOT NULL DEFAULT 'tidak',
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `polling`
--

INSERT INTO `polling` (`pid`, `pjudul`, `ppilihan`, `pjawaban`, `created`, `public`) VALUES
(1, 'Bagaimana Menurut Anda AuraCMS 2.2 ini', 'Bagus#Jelek#Biasa#Gak Menarik#No Comment', '1555#218#220#153#74', '17-Jul-2007', 'ya'),
(2, 'Setujukan Jika AuraCMS memakai framework Code Igniter', 'Setuju Sekali#Tidak Setuju#No Comment', '773#115#113', '03-May-2009', 'tidak');

-- --------------------------------------------------------

--
-- Table structure for table `polling_ip`
--

CREATE TABLE IF NOT EXISTS `polling_ip` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kd_polling` varchar(10) NOT NULL DEFAULT '',
  `ip` varchar(255) NOT NULL DEFAULT '',
  `timeout` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `polling_ip`
--

INSERT INTO `polling_ip` (`id`, `kd_polling`, `ip`, `timeout`) VALUES
(1, '51', '192.168.1.96, 202.51.233.167', 1187262141);

-- --------------------------------------------------------

--
-- Table structure for table `posted_ip`
--

CREATE TABLE IF NOT EXISTS `posted_ip` (
  `id` bigint(21) NOT NULL AUTO_INCREMENT,
  `file` varchar(100) NOT NULL DEFAULT '',
  `ip` varchar(100) NOT NULL DEFAULT '',
  `time` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5542 ;

--
-- Dumping data for table `posted_ip`
--

INSERT INTO `posted_ip` (`id`, `file`, `ip`, `time`) VALUES
(5540, 'shoutbox', '127.0.0.1', 1282924042),
(5541, 'guestbook', '127.0.0.1', 1282924092);

-- --------------------------------------------------------

--
-- Table structure for table `sensor`
--

CREATE TABLE IF NOT EXISTS `sensor` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `word` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `sensor`
--

INSERT INTO `sensor` (`id`, `word`) VALUES
(1, 'Kontol'),
(2, 'Anjing'),
(3, 'Anjeng'),
(4, 'anjrit'),
(5, 'memek'),
(6, 'tempek'),
(7, 'Bangsat'),
(8, 'fuck'),
(9, 'eSDeCe');

-- --------------------------------------------------------

--
-- Table structure for table `shoutbox`
--

CREATE TABLE IF NOT EXISTS `shoutbox` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `waktu` varchar(88) NOT NULL DEFAULT '',
  `nama` varchar(20) NOT NULL DEFAULT '',
  `email` varchar(50) NOT NULL DEFAULT '',
  `isi` varchar(255) NOT NULL DEFAULT '',
  `ket` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `shoutbox`
--

INSERT INTO `shoutbox` (`id`, `waktu`, `nama`, `email`, `isi`, `ket`) VALUES
(1, 'Jumat, 27 Agustus 2010  22:37:22', 'Iwan', 'admin@auracms.org', 'Tes Shoutbox AuraCMS 2.3 Oke :)', '127.0.0.1|Mozilla5.0 Windows; U; Windows NT 5.1; en-US; rv:1.9.2.6 Gecko20100625 Firefox3.6.6');

-- --------------------------------------------------------

--
-- Table structure for table `stat_browse`
--

CREATE TABLE IF NOT EXISTS `stat_browse` (
  `pjudul` varchar(255) NOT NULL DEFAULT '',
  `ppilihan` text NOT NULL,
  `pjawaban` text NOT NULL,
  `id` int(2) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `stat_browse`
--

INSERT INTO `stat_browse` (`pjudul`, `ppilihan`, `pjawaban`, `id`) VALUES
('Browser yang sering digunakan dalam mengakses halaman ini', 'Netscape#Opera#MSIE 4.0#MSIE 5.0#MSIE 6.0#Lynx#WebTV#Konqueror#bot#Other', '1531634#54153#271#27234#177254#37#0#165#153399#104955', 1),
('Operating system', 'Windows#Mac#Linux#FreeBSD#SunOS#IRIX#BeOS#OS/2#AIX#Other', '846230#3360#21271#36#32#4#6#1#1#1179025', 2),
('Pengunjung berdasarkan hari', 'Minggu#Senin#Selasa#Rabu#Kamis#Jumat#Sabtu', '278179#301172#310503#282325#304242#294655#279078', 3),
('Pengunjung berdasarkan bulan', 'Januari#Februari#Maret#April#Mei#Juni#Juli#Agustus#September#Oktober#November#Desember', '163304#162290#145755#139695#111050#174922#185199#197459#222044#241002#131865#175536', 4),
('Pengunjung berdasarkan jam', '0:00 - 0:59#1:00 - 1:59#2:00 - 2:59#3:00 - 3:59#4:00 - 4:59#5:00 - 5:59#6:00 - 6:59#7:00 - 7:59#8:00 - 8:59#9:00 - 9:59#10:00 - 10:59#11:00 - 11:59#12:00 - 12:59#13:00 - 13:59#14:00 - 14:59#15:00 - 15:59#16:00 - 16:59#17:00 - 17:59#18:00 - 18:59#19:00 - 19:59#20:00 - 20:59#21:00 - 21:59#22:00 - 22:59#23:00 - 23:59', '78671#73084#75431#77386#71036#72347#73837#76701#82151#92981#98353#96798#97523#101617#99837#95804#90482#86074#80473#83589#87675#87150#88827#82387', 5);

-- --------------------------------------------------------

--
-- Table structure for table `submenu`
--

CREATE TABLE IF NOT EXISTS `submenu` (
  `id` int(2) NOT NULL AUTO_INCREMENT,
  `menu` varchar(50) NOT NULL DEFAULT '',
  `url` varchar(127) NOT NULL DEFAULT '',
  `parent` int(2) NOT NULL DEFAULT '0',
  `published` int(1) NOT NULL DEFAULT '0',
  `ordering` int(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `submenu`
--

INSERT INTO `submenu` (`id`, `menu`, `url`, `parent`, `published`, `ordering`) VALUES
(1, 'Site Credit', 'site-credit.html', 1, 1, 3),
(2, 'Sejarah AuraCMS', 'sejarah-auracms.html', 1, 1, 2),
(3, 'Guestbook', 'guestbook.html', 2, 1, 7),
(4, 'Contact Us', 'contact.html', 2, 1, 6),
(5, 'Gallery', 'gallery.html', 2, 1, 8),
(6, 'Donasi', 'donasi.html', 1, 1, 4),
(7, 'Download', 'download.html', 1, 1, 5),
(8, 'Links', 'links.html', 1, 1, 6),
(9, 'About Me', 'index.php?pilih=hal&amp;id=32', 11, 1, 2),
(10, 'Site Credits', 'index.php?pilih=hal&amp;id=33', 11, 1, 1),
(11, 'Tentang auraCMS', 'tentang-auracms.html#translate-en', 1, 1, 1),
(12, 'Statistik Website', 'statistik.html', 2, 1, 9),
(23, 'Hosting IIX Support AuraCMS', 'http://duniamaya.web.id', 1, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_kalender`
--

CREATE TABLE IF NOT EXISTS `tbl_kalender` (
  `judul` varchar(255) NOT NULL DEFAULT '',
  `isi` text NOT NULL,
  `waktu_mulai` date NOT NULL DEFAULT '0000-00-00',
  `waktu_akhir` date NOT NULL DEFAULT '0000-00-00',
  `background` varchar(10) NOT NULL DEFAULT '#d1d1d1',
  `color` varchar(10) NOT NULL DEFAULT '',
  `id` int(12) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tbl_kalender`
--


-- --------------------------------------------------------

--
-- Table structure for table `topik`
--

CREATE TABLE IF NOT EXISTS `topik` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `topik` varchar(60) NOT NULL DEFAULT '',
  `ket` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `topik`
--

INSERT INTO `topik` (`id`, `topik`, `ket`) VALUES
(1, 'Berita Terkini', 'Berisi seputar berita terhangat saat ini'),
(2, 'Komputer Dan Internet', 'Berita tentang dunia komputer, hardware, software serta dunia internet'),
(3, 'Bisnis dan Ekonomi', 'Berita tentang bisnis dan ekonomi'),
(4, 'Olahraga', 'Berita dari dunia olahraga'),
(5, 'Sain dan Teknologi', 'Berita tentang teknologi baru yang berguna'),
(6, 'Tips And Trik', 'Kumpulan Artikel Beserta Tips dan triks'),
(7, 'AuraCMS', 'Berisi seputar tentang AuraCMS'),
(8, 'Politik', 'Politik'),
(10, 'Artikel', 'Artikel'),
(11, 'Seks', 'Seks'),
(12, 'Kesehatan', 'kesehatan');

-- --------------------------------------------------------

--
-- Table structure for table `useraura`
--

CREATE TABLE IF NOT EXISTS `useraura` (
  `UserId` int(15) NOT NULL AUTO_INCREMENT,
  `user` varchar(250) NOT NULL DEFAULT '',
  `password` text NOT NULL,
  `email` varchar(250) NOT NULL DEFAULT '',
  `web` varchar(250) NOT NULL DEFAULT '',
  `alamat` text NOT NULL,
  `avatar` varchar(250) NOT NULL DEFAULT '',
  `ym` varchar(250) NOT NULL DEFAULT '',
  `level` enum('Administrator','Editor','User') NOT NULL DEFAULT 'User',
  `tipe` varchar(250) NOT NULL DEFAULT '',
  `negara` varchar(50) NOT NULL DEFAULT '',
  `buddylist` varchar(250) NOT NULL DEFAULT '{"Admin":["admin","ridwan","arif","agus"]}',
  `is_online` int(5) NOT NULL DEFAULT '0',
  `last_ping` text NOT NULL,
  `nama` varchar(50) NOT NULL,
  `telepon` varchar(20) NOT NULL,
  `start` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `exp` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`UserId`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `useraura`
--

INSERT INTO `useraura` (`UserId`, `user`, `password`, `email`, `web`, `alamat`, `avatar`, `ym`, `level`, `tipe`, `negara`, `buddylist`, `is_online`, `last_ping`, `nama`, `telepon`, `start`, `exp`) VALUES
(1, 'admin', '09579f50d2ff5fd91574df217b0f036b', 'admin@auracms.org', 'http://auracms.org', 'Purwokerto', '', 'iwansusyanto', 'Administrator', 'aktif', 'Indonesia', '{"Admin":["admin","ridwan","arif","agus"]}', 0, '', 'Iwan Susyanto', '081327575145', '2010-08-27 00:00:00', '2034-08-27 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `usercounter`
--

CREATE TABLE IF NOT EXISTS `usercounter` (
  `id` tinyint(11) NOT NULL AUTO_INCREMENT,
  `ip` text NOT NULL,
  `counter` int(11) NOT NULL DEFAULT '0',
  `hits` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `usercounter`
--

INSERT INTO `usercounter` (`id`, `ip`, `counter`, `hits`) VALUES
(1, '127.0.0.1-74.63.226.246-125.166.242.21-38.99.186.20-125.163.153.227-125.166.238.208-', 1, 118);

-- --------------------------------------------------------

--
-- Table structure for table `useronline`
--

CREATE TABLE IF NOT EXISTS `useronline` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipproxy` varchar(100) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `ipanda` varchar(100) DEFAULT NULL,
  `proxyserver` varchar(100) DEFAULT NULL,
  `timevisit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ipanda` (`ipanda`),
  KEY `timevisit` (`timevisit`),
  KEY `ipproxy` (`ipproxy`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `useronline`
--

INSERT INTO `useronline` (`id`, `ipproxy`, `host`, `ipanda`, `proxyserver`, `timevisit`) VALUES
(5, '127.0.0.1', 'localhost', '127.0.0.1', '', 1282942845);

-- --------------------------------------------------------

--
-- Table structure for table `useronlineday`
--

CREATE TABLE IF NOT EXISTS `useronlineday` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipproxy` varchar(100) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `ipanda` varchar(100) DEFAULT NULL,
  `proxyserver` varchar(100) DEFAULT NULL,
  `timevisit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ipanda` (`ipanda`),
  KEY `timevisit` (`timevisit`),
  KEY `ipproxy` (`ipproxy`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `useronlineday`
--

INSERT INTO `useronlineday` (`id`, `ipproxy`, `host`, `ipanda`, `proxyserver`, `timevisit`) VALUES
(1, '127.0.0.1', 'localhost', '127.0.0.1', '', 1282942845);

-- --------------------------------------------------------

--
-- Table structure for table `useronlinemonth`
--

CREATE TABLE IF NOT EXISTS `useronlinemonth` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ipproxy` varchar(100) DEFAULT NULL,
  `host` varchar(100) DEFAULT NULL,
  `ipanda` varchar(100) DEFAULT NULL,
  `proxyserver` varchar(100) DEFAULT NULL,
  `timevisit` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ipanda` (`ipanda`),
  KEY `timevisit` (`timevisit`),
  KEY `ipproxy` (`ipproxy`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `useronlinemonth`
--

INSERT INTO `useronlinemonth` (`id`, `ipproxy`, `host`, `ipanda`, `proxyserver`, `timevisit`) VALUES
(1, '127.0.0.1', 'localhost', '127.0.0.1', '', 1282942845);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
