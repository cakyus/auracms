<?


include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/fungsi.php';
include '../../includes/mysql.php';
include '../../includes/json.php';
include 'gallery.config.php';


		
define("GIS_GIF", 1);
define("GIS_JPG", 2);
define("GIS_PNG", 3);



if (!cek_login () && @$_SESSION['LevelAkses'] != 'Administrator'){
   exit;
}

if (!isset($_SESSION['mod_ajax'])){
exit;	
}




$admin = '';


switch(@$_GET['action']){
	
case 'fancy':

if (empty($_GET['kategory'])){
	exit;
}



include "../../includes/hft_image.php";		

if (isset($_FILES['Filedata']['name']))
	{
		$file = $_FILES['Filedata']['tmp_name'];
		$error = false;

		/**
		 * THESE ERROR CHECKS ARE JUST EXAMPLES HOW TO USE THE REPONSE HEADERS
		 * TO SEND THE STATUS OF THE UPLOAD, change them!
		 *
		 */

		if (!is_uploaded_file($file))
		{
			$error = '400 Bad Request';
		}
		
		/*
		//untuk upload images
		if (!$error && !($size = @getimagesize($file)))
		{
			$error = '409 Conflict';
		}
		if (!$error && !in_array($size[2], array(1, 2, 3, 7, 8) ) )
		{
			$error = '415 Unsupported Media Type';
		}
		if (!$error && ($size[0] < 25) || ($size[1] < 25))
		{
			$error = '417 Expectation Failed';
		}
		*/

		
		

		if ($error)
		{
			/**
			 * ERROR DURING UPLOAD, one of the validators failed
			 * 
			 * see FancyUpload.js - onError for header handling
			 */
			
			//header('HTTP/1.0 ' . $error);
			//die('Error ' . $error);
		}
		else
		{
			/**
			 * UPLOAD SUCCESSFULL AND VALID
			 *
			 * Use move_uploaded_file here to save the uploaded file in your directory
			 */
		
		$w = date('Ymdhis').'@';
$userfile_tmp = $_FILES['Filedata']['tmp_name'];
$finame = $_FILES['Filedata']['name'];
$finame = str_replace (' ', '_', $finame);
$finame = str_replace ('@', '', $finame);


$kode = 'htTemP_'.md5(rand(1000, 10000000));

$destination = $Tpath.$w.$finame;

$thumb = $Tnpath.$w.$finame;
$sementara = $tmp."$kode(#)$finame";
move_uploaded_file($userfile_tmp, $sementara);

$testsize = getimagesize ($sementara);
if (is_array ($testsize)) list ($foo, $width, $bar, $height) = explode ("\"", $testsize[3]);
$filessize = filesize($sementara);
$Mime_pic = false;
if ($testsize[2] == GIS_JPG or $testsize[2] == GIS_PNG or $testsize[2] == GIS_GIF){
$Mime_pic = true;	
}else {
$width = '';
$height = '';
}

if ($filessize <= $max_size && $width <= $max_width && $height <= $max_height && $Mime_pic == true){

//copy ($sementara ,$destination);
$CONFIG['resizewidth'] = $width < $CONFIG['resizewidth'] ? $width : $CONFIG['resizewidth'];
$im = new hft_image ($sementara);
$im -> set_parameters(85);
$im -> resize($CONFIG['resizewidth'], $CONFIG['resizewidth'], 0);
$im -> imagecreatefromfile($sementara);
$im -> output_resized($destination);





$filessizes = filesize($destination);
$simpanfile = $w.$finame;

if ($width < $CONFIG['thumb_width']) {
$CONFIG['thumb_width'] = $width;
}
	

$im = new hft_image ($sementara);
$im -> set_parameters($CONFIG['jpeg_qual']);
$im -> resize($CONFIG['thumb_width'], $CONFIG['thumb_width'], 0);
$im -> imagecreatefromfile($sementara);
$im -> output_resized($thumb);

$date = time ();
$kategory = $_GET['kategory'];


$type = "aktif";
list($width, $height, $type, $attr) = getimagesize ($destination);
$hasil = mysql_query("INSERT INTO `mod_gallery` (name, width, height, modified, size, kid) VALUES ('$simpanfile', '$width', '$height', '$date', '$filessizes', '$kategory')");
$query = mysql_query("UPDATE `mod_gallery_kat` SET total=total+1 WHERE id='$kategory'");            



}#end if File size
else {
@unlink ($sementara);
$error = '415 Unsupported Media Type'; 
header('HTTP/1.0 ' . $error);
die('Error ' . $error);	
}
@unlink ($sementara);

	  
		}

		die('Upload Successfull');

	}

break;	
	
	
case 'upload':




include "../../includes/hft_image.php";		
$_FILES['namafile']['name'] = is_array(@$_FILES['namafile']['name']) ? @$_FILES['namafile']['name'] : array ();	
for ($e=0; $e<=count(@$_FILES['namafile']['name']); $e++){

if (!empty ($_FILES['namafile']['name'][$e])){
$w = date('Ymdhis').'@';
$userfile_tmp = $_FILES['namafile']['tmp_name'][$e];
$finame = $_FILES['namafile']['name'][$e];
$finame = str_replace (' ', '_', $finame);
$finame = str_replace ('@', '', $finame);


$kode = 'htTemP_'.md5(rand(1000, 10000000));

$destination = $Tpath.$w.$finame;
$thumb = $Tnpath.$w.$finame;
$sementara = $tmp."$kode(#)$finame";
move_uploaded_file($userfile_tmp, $sementara);

$testsize = getimagesize ($sementara);
if (is_array ($testsize)) list ($foo, $width, $bar, $height) = explode ("\"", $testsize[3]);
$filessize = filesize($sementara);
$Mime_pic = false;
if ($testsize[2] == GIS_JPG or $testsize[2] == GIS_PNG or $testsize[2] == GIS_GIF){
$Mime_pic = true;	
}else {
$width = '';
$height = '';
}

if ($filessize <= $max_size && $width <= $max_width && $height <= $max_height && $Mime_pic == true){
//echo "$sementara ,$destination";exit;
//copy ($sementara,$destination);
$CONFIG['resizewidth'] = $width < $CONFIG['resizewidth'] ? $width : $CONFIG['resizewidth'];
$im = new hft_image ($sementara);
$im -> set_parameters(85);
$im -> resize($CONFIG['resizewidth'], $CONFIG['resizewidth'], 0);
$im -> imagecreatefromfile($sementara);
$im -> output_resized($destination);






$filessizes = filesize($destination);
$simpanfile = $w.$finame;

if ($width < $CONFIG['thumb_width']) {
$CONFIG['thumb_width'] = $width;
}
	

$im = new hft_image ($sementara);
$im -> set_parameters($CONFIG['jpeg_qual']);
$im -> resize($CONFIG['thumb_width'], $CONFIG['thumb_width'], 0);
$im -> imagecreatefromfile($sementara);
$im -> output_resized($thumb);

$date = time ();
$kategory = $_POST['kategori'];

$type = "aktif";
list($width, $height, $type, $attr) = getimagesize ($destination);

$hasil = mysql_query("INSERT INTO `mod_gallery` (name, width, height, modified, size, kid) VALUES ('$simpanfile', '$width', '$height', '$date', '$filessizes', '$kategory')");
$query = mysql_query("UPDATE `mod_gallery_kat` SET total=total+1 WHERE id='$kategory'");            

$admin .="<div class=\"border\"><!-- Start standard table --><p>
        <TABLE class=maintable cellSpacing=1 cellPadding=0 width=100% align=center>
                    <TBODY>
                    <TR>
                      <TD class=tableh2_compact colSpan=2><B>Informasi 
                      file</B></TD></TR><TR>
                     <TD class=tableb_compact vAlign=center noWrap rowspan=6><p align=center><img src='$Tnpath$simpanfile'><p></TD>
                      <TD class=tableb_compact>Nama File : $finame</TD></TR>
                    <TR>
                      <TD class=tableb_compact>Dimensi : $width x $height Pixel</TD></TR>
                    <TR>
                      <TD class=tableb_compact>Filesize : ".fsize($filessizes)."</TD></TR>                   
                    <TR>
                      <TD class=tableb_compact>".transCAL ($date,true)." WIB</TD></TR></TBODY></TABLE><!-- End standard table --></div>";	

}#end if File size
unlink ($sementara);
}#rnd If empty

}##end For
echo '
<html>
<head>
<title>Uploaded</title>
<style>
body,td,tr,p{
font-family:verdana;
font-size:9px;
}
table{
border:1px solid #efefef;	
}
</style>
</head>
</body>
';	
echo $admin;		
echo '
</body></html>

';

break;	
	
	
	
	
}





?>