<?

/**
 * AuraCMS v2.2
 * auracms.org
 * December 03, 2007 07:29:56 AM 
 * Author: 	Arif Supriyanto     - arif@ayo.kliksini.com  - +622470247569
 *		Iwan Susyanto, S.Si - admin@auracms.org      - 0281 327 575 145
 *		Rumi Ridwan Sholeh  - floodbost@yahoo.com    - 0817 208 401
 * 		http://www.auracms.org
 *		http://www.iwan.or.id
 *		http://www.ridwan.or.id
 */

include 'includes/config.php';
include 'includes/mysql.php';

$_GET['id'] = !isset($_GET['id']) ? null : $_GET['id'];
$id = int_filter($_GET['id']);

global $koneksi_db,$translateKal,$url_situs;


$hasil = $koneksi_db->sql_query( "SELECT * FROM artikel WHERE id='$id' AND publikasi=1" );

while ($data = $koneksi_db->sql_fetchrow($hasil)) {

		$topik=$data[7];
		$topik1 = $koneksi_db->sql_query( "SELECT * FROM topik WHERE id='$topik'" );

		while ($topik2 = $koneksi_db->sql_fetchrow($topik1)) {
			$topikku=$topik2[1];
		}

		echo "<html><head><title>$judul_situs : $data[1]</title></head><body>";
		echo "<table align=\"center\" width=\"100%\" bgcolor=\"black\" cellspacing=\"1\" cellpadding=\"4\"><tr><td bgcolor=\"white\"><b>$judul_situs</b><br>Rubrik : $topikku</td></tr>";
		echo "<tr><td bgcolor=\"white\"><big><big>$data[1]</big></big><br>";
		
		echo $data[5].' - by : <a href="/index.php?pilih=news&mod=yes&aksi=pesan&id='.$data[0].'">'.$data[3].'</a></td></tr><tr><td bgcolor="white"><blockquote>'.gb1($data[2]);
		echo "</td></tr><tr><td bgcolor=\"white\">$judul_situs : <a href=$url_situs>$url_situs</a><br>";
		echo 'Versi Online : <a href="'.$url_situs.'/article/'.$id.'/'.AuraCMSSEO($data[1]).'.html">'.$url_situs.'/article/'.$id.'/'.AuraCMSSEO($data[1]).'.html</a></td></tr></table></body</html>';
}

if (isset($_GET['id'])){
echo "<script language=javascript>
function printWindow() {
bV = parseInt(navigator.appVersion);
if (bV >= 4) window.print();}
printWindow();
</script>";
}

?>