<?
if (!defined('AURACMS_admin')) {
	Header("Location: ../index.php");
	exit;
}

if (!cek_login ()){
   $admin .='<p class="judul">Access Denied !!!!!!</p>';
   exit;
}


$index_hal = 1;


$admin = '<h4 class="bg">Halaman Web Manager</h4>';
$admin .='<div class="border"><a href="?pilih=admin_pages"><b>Home</b></a> | <a href="?pilih=admin_pages&amp;aksi=add"><b>Buat Halaman Web Baru</b></a></div>';

$admin .= "\n";


$JS_SCRIPT = <<<js

<script language="javascript" type="text/javascript" src="editor/tinymce/tiny_mce.js"></script>

<script language="javascript" type="text/javascript">
tinyMCE.init({
	theme : "advanced",
	mode : "textareas",
	language : "en",
	editor_selector : "mceEditor",
	height : "420",
	plugins : "emotions,insertdatetime,preview,searchreplace,table,multimedia,quoteubb,addmore,uploader,filedownload",
	force_br_newlines : true,
	force_p_newlines : false,
	convert_fonts_to_spans : true,
	theme_advanced_toolbar_location : "top",
	theme_advanced_statusbar_location : "none",
	theme_advanced_toolbar_align : "left",
	auto_focus : "mce_editor_0",
	theme_advanced_disable : "formatselect,styleselect,help",
	theme_advanced_buttons1_add : "forecolor,backcolor,fontselect,fontsizeselect",
	theme_advanced_buttons2_add : "separator,table,quoteubb,emotions,multimedia,insertdate,inserttime,filedownload,insertseparator,insertnewpage",
	theme_advanced_buttons3_add : "search,replace,preview,uploader"
});
</script>
js;





switch(@$_GET['aksi']) {
case 'del':
if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 1) {
$id = int_filter($_GET['id']);	
$query = mysql_query("DELETE FROM `halaman` WHERE `id` = '$id'");
header("location:?pilih=admin_pages");
exit;
}

break;
case 'edit':
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
$id = int_filter($_GET['id']);	


if (isset($_POST['submit'])) {
$judul = cleantext($_POST['judul']);
$konten = $_POST['konten'];

$query = mysql_query("UPDATE `halaman` SET `judul` = '$judul', `konten` = '$konten' WHERE `id` = '$id'");	
header("location:?pilih=admin_pages");
exit;
}

$query = mysql_query("SELECT * FROM `halaman` WHERE `id` = '$id'");	
$data = mysql_fetch_assoc($query);

$script_include[] = $JS_SCRIPT;
$admin .= '<div class="border">';
$admin .= '<form method="post" action="#">';
$admin .= 'Judul Halaman Web : <br />';
$admin .= '<input type="text" name="judul" value="'.stripslashes(htmlspecialchars($data['judul'])).'" size="50" /><br />';
$admin .= 'Isi Halaman Web : <br />';
$admin .= '<textarea id="elm1" name="konten" rows="15" cols="80" style="width: 70%">';
$admin .= htmlspecialchars($data['konten']);
$admin .= '</textarea>';
$admin .= '<br /><br />';
$admin .= '<input type="submit" name="submit" value="edit" />';
$admin .= '</form></div>';

}
break;	
case 'add':
if (isset($_POST['submit'])) {
$judul = cleantext($_POST['judul']);
$konten = $_POST['konten'];

$error = '';

if (empty($judul)) {
	$error .= '- Error: Judul harus Diisi<br />';
}

	if ($error != '') {
	 $admin .= '<div class="error">'.$error.'</div>';
	} else {

	$query = mysql_query("INSERT INTO `halaman` (`judul`,`konten`) VALUES ('$judul','$konten')");	
		if ($query) {
		$admin .= '<div class="sukses">Sukses Tambah Halaman</div>';
			} 
			else {
				$admin .= '<div class="error">'.mysql_error().'</div>';
			}

}

}
$script_include[] = $JS_SCRIPT;
$admin .= '<div class="border">';
$admin .= '<form method="post" action="#" name="editentry" id="editentry" >';
$admin .= 'Judul Halaman Web : <br />';
$admin .= '<input type="text" name="judul" value="'.stripslashes(htmlspecialchars(@$_POST['judul'])).'" size="50" /><br />';
$admin .= 'Isi Halaman Web : <br />';
$admin .= '<textarea class="mceEditor"  name="konten" rows="15" cols="80" style="width: 70%">';
$admin .= htmlspecialchars(@$_POST['konten']);
$admin .= '</textarea>';
$admin .= '<br /><br />';
$admin .= '<input type="submit" name="submit" value="add" />';
$admin .= '</form></div>';

break;	
default:

$query = mysql_query("SELECT `id`,`judul` FROM halaman ORDER BY id");
$admin .= '
<table cellspacing="0" style="width:100%">
	<tr bgcolor="#d1d1d1">
	<th style="width:25px;text-align:center;padding:10px 5px 10px 5px;border-left:1px solid #ccc;border-top:1px solid #ccc;">No</th>
	<th style="text-align:left;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Judul Halaman</th>
	<th style="text-align:center;padding:10px 5px 10px0 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;">Action</th>
	</tr>';
$no =1;
while($data = mysql_fetch_assoc($query)) {
$warna = empty ($warna) ? 'bgcolor="#efefef"' : '';
if ($data['id'] == 1){
	$deleted = '';	
}else {
	$deleted = '<a href="?pilih=admin_pages&amp;aksi=del&amp;id='.$data['id'].'" onclick="return confirm(\'Apakah Anda Ingin Menghapus Data Ini ?\')" style="color:red">Delete</a> - ';		
}	
$admin .= '
	<tr '.$warna.'>
	<td style="width:25px;text-align:center;padding:2px;border-left:1px solid #ccc;border-top:1px solid #ccc;">'.$no.'</td>
	<td style="text-align:left;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;"><a href="index.php?pilih=hal&amp;id='.$data['id'].'" title="'.$data['judul'].'">'.limittxt($data['judul'],40).'</a></td>
	<td style="text-align:center;padding:10px 5px 10px0 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$deleted.'<a href="?pilih=admin_pages&amp;aksi=edit&amp;id='.$data['id'].'">Edit</a> - <a href="?pilih=admin_menu&amp;aksi=add&amp;url=pages-'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html">Buat Menu</a> - <a href="?pilih=admin_menu&amp;aksi=addsub&amp;url=pages'.$data['id'].'-'.AuraCMSSEO($data['judul']).'.html">Buat Sub Menu</a></td>
	</tr>';
$no++;		
}
$admin .= '<tr><td colspan="3" style="width:25px;text-align:center;padding:5px;border-top:1px solid #ccc;">&nbsp;</td></tr></table>';

break;
}

echo $admin;
?>