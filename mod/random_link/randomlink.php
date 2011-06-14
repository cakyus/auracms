<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
ob_start();
global $koneksi_db;

$query  = "SELECT * FROM mod_link WHERE public='1' ORDER BY RAND() LIMIT 1";
$hasil  = mysql_query($query);
$banyak = mysql_num_rows($hasil);


$data = mysql_fetch_array($hasil);
$id    = $data['id'];
$judul = $data['judul'];	
$url   = $data['url'];
$hit   = $data['hit'];
$date  = date('d-M-Y H:i:s',$data['date']);


if ($banyak > 0){
echo '
<div align="center"><a href="url-'.$id.'-'.AuraCMSSEO($judul).'.html" target="_blank" title="'.htmlentities($judul).'">'.htmlentities($judul).'<br />
<img src="http://open.thumbshots.org/image.pxf?url='.$url.'" title="'.$judul.'" border="0" alt="" /></a>
<br />View : '.$hit.' x hits<br />Join : '.$date.'<br /></div>';
}

$out = ob_get_contents();
ob_end_clean();
?>