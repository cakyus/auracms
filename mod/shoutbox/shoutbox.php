<?
if (defined('AURACMS_CONTENT')) {
exit;	
}
ob_start('ob_gzhandler');
include "../../includes/session.php";
include '../../includes/config.php';
include '../../includes/fungsi.php';
include '../../includes/mysql.php';


$content = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">
<head>
<title>::ridwan website::</title>


<style type="text/css">

BODY {
margin : 0 0 0 0;
padding : 0 0 0 0;
width : auto; 
color:#666666;

}
td,body
{
font-family :  Verdana;
font-size : 10px;
font-weight : normal;
color:#666666;
}
a:link
{
color:#517DBF;
text-decoration:none;
}
a:visited
{
color:#517DBF;
text-decoration:none;
}
a:hover
{
color:red;
text-decoration:underline;
}
</style>
</head>
<body>';
	
	
function hideEmail($email){
if (empty($email)) return '';
list($user,$domain) = explode ('@',$email);
if (empty($user) or empty($domain)) return $email;
$lenUSER = strlen($user);
$rand = rand(1,$lenUSER);
$out = '';
for($i=0;$i<$rand;$i++){
$out .= '&#'.ord($user[$i]).';';	
}
$out .= substr($user,$rand);	
return $out.'@'.$domain;
}


$content .= '<table style="width:100%">';



global $koneksi_db,$maxadmindata;

$perintah = "SELECT * FROM shoutbox ORDER BY id DESC LIMIT 30";
$hasil = mysql_query( $perintah );
$no = 0 ;
while ($data = mysql_fetch_array($hasil)) {
$WAKTu = $data['waktu'];	
$NAMA = $data['nama'];
$EMAIL = hideEmail($data['email']);
$ISI = $data['isi'];
$KET = $data['ket'];
$pecah = explode ('|', $KET);

$ISI = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.){2}+)(\/)?(\S+)?/i', '<a href="\0" target="_blank">Klik Here</a>', $ISI);	
$ISI = wordwrap($ISI, 20, ' ', 1);

if ($no % 2 == 0) {
$class = 'bgcolor="#efefef"';
}else {
$class = '';	
}	
$content .= "<tr $class><td><a href=\"mailto:$EMAIL\" title=\"Ip: $pecah[0]
$pecah[1]\">" .substr($NAMA,0,15)."</a> : $ISI</td></tr>\n";
	$content .= "<tr $class><td><span style=\"font-color:gray\">$WAKTu</span></td></tr>\n";
	$content .= "<tr $class><td></td></tr>\n";	
$no++;	
}
mysql_free_result ($hasil);


$content .= '</table>';
echo $content;

echo '</body></html>';
?>