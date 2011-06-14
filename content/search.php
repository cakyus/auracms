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

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

global $koneksi_db,$maxdata;
$tengah ='<h4 class="bg">Hasil Pencarian</h4>';
$query = cleartext($_GET['query']);
if($query=='' or !isset($query)){
	$tengah .="<div class=\"error\">Tidak Ada Pencarian</div>";
}else{

	$limit = 10;
	$s1 = '';
	$query = htmlentities($query);
	
	
	$hasil= $koneksi_db->sql_query("SELECT * FROM artikel WHERE ((judul LIKE '%$query%' OR konten LIKE '%$query%' OR user LIKE '%$query%')AND publikasi=1)");
	$jumlah= $koneksi_db->sql_numrows($hasil);

	if ($jumlah<1){
		$s1="tidak ada";
	}

	$a = new paging ($limit);

if (!$s1) {

	$tengah .='<div class="border">';
	$tengah .="Yang dicari <b>\"$query\"</b>";
	$tengah .='</div>';
	
	
	$offset = int_filter(@$_GET['offset']);
	$pg		= int_filter(@$_GET['pg']);
	$stg	= int_filter(@$_GET['stg']);
	
	$hasil2= $koneksi_db->sql_query("SELECT * FROM artikel WHERE ((judul LIKE '%$query%' OR konten LIKE '%$query%' OR user LIKE '%$query%')AND publikasi=1) ORDER By id LIMIT $offset,$limit");

	


	$tengah .='<div class="border">';
	$tengah .= "Ditemukan <b>".$jumlah."</b> artikel mengandung kata: <b>$query</b>";
	$tengah .='</div>';
	
	$tengah .='<div class="border">';
	while($data = $koneksi_db->sql_fetchrow($hasil2)){

		$tengah .= "<p class=\"konten\"><a href=\"?pilih=news&amp;mod=yes&amp;aksi=lihat&amp;id=$data[0]\">$data[1]</a><br />";
		
		$data[5]= datetimes($data['tgl']);
		$tengah .= "<span class=\"keterangan\">$data[5] - by : <a href=\"?pilih=news&amp;mod=yes&amp;aksi=pesan&amp;id=$data[0]\">$data[3]</a></span></p>";	
		}
	$tengah .='</div>';


	if($jumlah>=10){

	if (empty($_GET['offset']) and !isset ($_GET['offset'])) {
	$offset = 0;
	}
	if (empty($_GET['pg']) and !isset ($_GET['pg'])) {
	$pg = 1;
	}
	if (empty($_GET['stg']) and !isset ($_GET['stg'])) {
	$stg = 1;
	}
	
	
	
	$tengah .='<div class="border" style="text-align:center;">';
	$tengah .= $a-> getPaging($jumlah, $pg, $stg);
	$tengah .='</div>';

	}
} //akhir if $s1


if ($s1) {
		$tengah.='<div class="error">Pencarian dengan kata kunci : <b>'.$query.'</b> <br />tidak ditemukan</div>';

}

}

echo $tengah;

?>