<?

$perintah = "SELECT * FROM usercounter WHERE id=1";
$hasil = mysql_query( $perintah );
while ($data = mysql_fetch_row($hasil)) {
	$hits=$data[3];
}

?>
