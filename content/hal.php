<?

/**
 * Modul Page for AuraCMS v2.2
 * auracms.org
 * December 03, 2007 09:57:12 AM 
 * Author: 	Iwan Susyanto, S.Si - admin@auracms.org
 *		Arif Supriyanto - arif@ayo.kliksini.com
 *		Rumi Ridwan Sholeh - floodbost@yahoo.com
 * 		http://www.auracms.org
 * 		http://www.semarang.tk
 *		http://www.iwan.or.id
 *		http://www.ridwan.or.id
 *		http://www.auracms.opensource-indonesia.com
 *
 */

if (!defined('AURACMS_CONTENT')) {
	Header("Location: ../index.php");
	exit;
}

$index_hal=1;

$tengah = '';
global $koneksi_db;

$id = int_filter($_GET['id']);
$hasil = $koneksi_db->sql_query( "SELECT judul,konten FROM halaman WHERE id='$id'" );
$data = $koneksi_db->sql_fetchrow($hasil) ;

$judulnya = $data['judul'];

if (empty ($judulnya)){
		$tengah .='<div class="error"><center>Access Denied<br /><br />Regard<br /><br />Iwan Susyanto,S.Si<br />iwansusyanto@yahoo.com</center></div>';
}else {




$tengah .='<h4 class="bg">'.$data['judul'].'</h4>';

$tengah .='<div class="border">';
$tengah .= $data['konten'];
$tengah .='</div>';

}
echo $tengah;

?>