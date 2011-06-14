<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
ob_start();
global $koneksi_db,$maxdata;

$jml=$maxdata;
$awal=0;

$perintah="SELECT * FROM artikel WHERE publikasi=1 ORDER BY id DESC LIMIT $awal,$jml";
$hasil = mysql_query( $perintah );
echo "<ul>";

while ($data = mysql_fetch_row($hasil)) {
$judul = AuraCMSSEO($data[1]);
$id = $data[0];
		echo '<li><a href="article-'.$id.'-'.$judul.'.html" title="'.$data[1].'">'.limitTxt($data[1],19).'</a></li>';
	
}
echo "</ul>";
$out = ob_get_contents();
ob_end_clean();
?>