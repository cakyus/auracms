<?php

if(ereg(basename (__FILE__), $_SERVER['PHP_SELF']))
{
	header("HTTP/1.1 404 Not Found");
	exit;
}
$tengah  = '<h4 class="bg">Statistik Web</h4>';
$hasil = mysql_query("SELECT * FROM stat_browse");

$a =1;
while ($data = mysql_fetch_array($hasil)){
$PJUDUL = $data["pjudul"];
$PPILIHAN = explode("#", $data["ppilihan"]);
$PJAWABAN = explode("#", $data["pjawaban"]);
$jmlpil = count($PPILIHAN);
$JMLVOTE = array();
for($i=0;$i<$jmlpil;$i++)
{
	$JMLVOTE[$a] = $JMLVOTE[$a] + $PJAWABAN[$i];
}
// Jika tidak ada vote, tetapkan jumlah vote = 1 untuk menghindari pembagian dengan nol
if($JMLVOTE[$a] == 0)
{
	$JMLVOTE[$a] = 1;
}
$tengah .= '<div class="border"><strong>'.$PJUDUL.' :</strong></div>';
$tengah .= '<div class="border">';
$tengah .= '<table  border="0">';
for($i=0;$i<$jmlpil;$i++)
{
	$persentase = round($PJAWABAN[$i] / $JMLVOTE[$a] * 100, 2);
	$tengah .= '<tr>';
	$tengah .= '<td>'.$PPILIHAN[$i].'</td>';
	$loop = floor($persentase)* 2;
	$tengah .= '<td><img src="/images/aqua.gif" alt="" width="'.$loop.'" height="9" /></td>';
	$tengah .= '<td>'.$PJAWABAN[$i] . ' = ('.$persentase.'%)</td>';
	$tengah .= '</tr>';
}
$tengah .= '</table>';
$tengah .= '</div>';
$tengah .= '<div class="border">Total '.$JMLVOTE[$a].'</div>';
$a++;

}
echo $tengah;
?>