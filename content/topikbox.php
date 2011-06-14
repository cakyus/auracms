<?
if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}

global $koneksi_db;

$title = "Rubrik";

$perintah = "SELECT * FROM topik ORDER BY id";
$hasil = $koneksi_db->sql_query( $perintah );

$topikbox = "<ul>";
while ($data = $koneksi_db->sql_fetchrow($hasil)) {

$judul = AuraCMSSEO($data['topik']);
$id      = $data['id'];

		$topikbox.= '<li><a href="category-'.$id.'-'.$judul.'.html" title="'.$data['topik'].'">'.$data['topik'].'</a></li>';

	}
$topikbox.= "</ul>";

kotakjudul ($title, $topikbox);

?>