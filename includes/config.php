<?

/**
 * AuraCMS v2.2
 * auracms.org
 * December 03, 2007 07:29:56 AM 
 * Author:  Arif Supriyanto     - arif@ayo.kliksini.com  - +622470247569
 *  Iwan Susyanto, S.Si - admin@auracms.org      - 081 327 575 145
 *  Rumi Ridwan Sholeh  - floodbost@yahoo.com    - 0817 208 401
 *   http://www.auracms.org
 *  http://www.iwan.or.id
 */

error_reporting(0);

/*---------------------------------------------------------------------------------
 konfigurasi database
---------------------------------------------------------------------------------*/
define('AURACMS_FUNC', true);

$mysql_user = 'root';
$mysql_password = '';
$mysql_database = 'auracms';
$mysql_host = 'localhost';

/*---------------------------------------------------------------------------------
 konfigurasi situs dan email
---------------------------------------------------------------------------------*/

$email_master='admin@auracms.org, ridwan@auracms.org';
$theme='auracms-elegant';
$judul_situs='AuraCMS : Indonesia Content Management System';

// $url_situs
// <protocol>://<hostName>[:<portNumber>]/path/to/cms
$url_situs = 'http://'
            .$_SERVER["SERVER_NAME"]
            .':'.$_SERVER["SERVER_PORT"]
            .dirname($_SERVER["REQUEST_URI"])
            ;

$slogan = 'Free and Easy To Use';
$adminfile = 'admin'; //silahkan di ganti dan jangan lupa merename file admin.php  sesuai dg yang anda ganti
//$editor ='1';  //Jika menggunakan WYSIWYG isi 1 jika tidak 0
$name_blocker = 'root,admin,god,administrator,anonymous,aura,auracms,iwan,ridwan,arif,sexecutor,master';
$mail_blocker = '';

$_META['description'] = 'AuraCMS adalah CMS indonesia yang simpeldanmudah digunakan';
$_META['keywords'] = 'ada
adalah
akan
alamat
anak
anda
animasi
apa
artikel
baca
bagaimana
banyak
baru
belajar
beli
berita
bermain
bikin
bisa
bisnis
buat
buku
cara
cari
cepat
chatting
daftar
dalam
dan
dapat
dari
dengan
detik
doa
fotografi
gambar
hacker
halaman
handphone
harga
iklan
indonesia
indonesian
informasi
ingin
ini
jadi
jakarta
jual
juga
kami
kartu
ke
kita
klik
komik
komputer
komunitas
kursus
lebih
majalah
mau
melalui
membaca
membuat
mencari
menggunakan
menjadi
milis
mobil
mudah
musik
nama
pada
panduan
permainan
pesan
puasa
ramadhan
saya
semua
sendiri
situs
teknik
tempat
tentang
terbaik
terbaru
tidak
trik
uang
untuk
vcd
versi
waktu
yang
yg
';

$maxkonten=50;
$maxadmindata = 20;
$maxdata = 10;
$translateKal = array( 'Mon' => 'Senin',
      'Tue' => 'Selasa',
      'Wed' => 'Rabu',
      'Thu' => 'Kamis',
      'Fri' => 'Jumat',
      'Sat' => 'Sabtu',
      'Sun' => 'Minggu',
      'Jan' => 'Januari',
      'Feb' => 'Februari',
      'Mar' => 'Maret',
      'Apr' => 'April',
      'May' => 'Mei',
      'Jun' => 'Juni',
      'Jul' => 'Juli',
      'Aug' => 'Agustus',
      'Sep' => 'September',
      'Oct' => 'Oktober',
      'Nov' => 'Nopember',
      'Dec' => 'Desember');


if (file_exists('includes/fungsi.php')){
 include 'includes/fungsi.php';
}

if (substr(phpversion(),0,3) >= 5.1) {
date_default_timezone_set('Asia/Jakarta');
        
        // $_basedir
        // basically, this is the cms real root path
        $_basedir = dirname($_SERVER['SCRIPT_FILENAME']).'/';
		
		set_include_path(get_include_path() . 
		  PATH_SEPARATOR . $_basedir . 'mod/phpids' . 
		  PATH_SEPARATOR . $_basedir . 'mod/phpids/lib'
		  );
		if (file_exists('mod/phpids/trace.php')){  
		include 'mod/phpids/trace.php';
		}elseif (file_exists('trace.php')){
		include 'trace.php'; 
		}else {}
	
}
?>
