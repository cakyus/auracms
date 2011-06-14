<?
if (!defined('AURACMS_admin')) {
    Header("Location: ../index.php");
    exit;
}

if (!cek_login ()){
   exit;
}
if ($_SESSION['LevelAkses']!="Administrator") {
	exit;
}


//$index_hal = 1;

$style_include[] =<<<style
<style type="text/css">
.tabel-modul tr.head {
height:20px;
background:#d1d1d1;
}
.tabel-modul tr.head td{
	border-right: 1px solid #d1d1d1;
	border-bottom: 1px solid #d1d1d1;
	border-top: 1px solid #d1d1d1;
	background: #efefef;
	padding-top:4px;
	padding-bottom:4px;
	padding-left:8px;
	padding-right:8px;
	color: #4f6b72;
	font-weight:bold;
	text-align: center;
	
}
.tabel-modul tr.head td.depan, tr.isi td.depan{
border-left: 1px solid #d1d1d1;
}
.tabel-modul tr.isi td{
border-right: 1px solid #d1d1d1;
	border-bottom: 1px solid #d1d1d1;
	padding-top:4px;
	padding-bottom:4px;
	padding-left:8px;
	padding-right:8px;
	color: #4f6b72;
}
</style>
style;

function form_select($name,$value = array()) {
	$t = '<select name="'.$name.'" size="1">';
	if (is_array($value)) {
		foreach($value as $key=>$val) {
				if (@$_POST[$name] == $key) {
				$t .= '<option value="'.$key.'" selected="selected">'.$val.'</option>';	
				}else {
				$t .= '<option value="'.$key.'">'.$val.'</option>';
				}
			}
	}
	$t .= '</select>';
	return $t;
}

$admin ='<h4 class="bg">Modul Manager </h4>';
$admin .='<div class="border"><a href="?pilih=admin_modul"><b>Home</b></a> | <a href="?pilih=admin_modul&amp;action=addmodul"><b>Buat Blok Modul Baru</b></a> | <a href="?pilih=admin_modul&amp;action=addblock"><b>Buat Blok content Baru</b></a></div>';

switch(@$_GET['action']) {
case 'addblock':

if (isset($_POST['submit'])) {
	$error = null;
	if (empty($_POST['title'])) {
		$error .= '- Error Judul blok tidak boleh kosong<br/>';
	}
	if (empty($_POST['modul'])) {
		$error .= '- Error isi blok tidak boleh kosong<br/>';
	}
	
	if ($error != '') {
		$admin .= '<div class="error">'.$error.'</div>';
	}else {
		$title = trim(strip_tags($_POST['title']));
		$modul = trim(strip_tags($_POST['modul']));
		$posisi = trim(strip_tags($_POST['posisi']));
		$cek = mysql_query("SELECT MAX(`ordering`) + 1 AS `ordering` FROM `modul` WHERE `posisi` = '$posisi'");
		$data = mysql_fetch_assoc($cek);
		$ordering = $data['ordering'];
		$insert = mysql_query("INSERT INTO `modul` (`modul`,`isi`,`posisi`,`ordering`,`type`) VALUES ('$title','$modul','$posisi','$ordering','block')");
		if ($insert) {
header("location: admin.php?pilih=admin_modul");
		exit;	
}else {
$admin .= '<div class="error">'.mysql_error().'</div>';	
}
	}
	
	
}
$admin .= '<fieldset style="padding:20px;width:80%;border: 1px solid #d1d1d1;">
<legend>Add block</legend>
<form method="post" action="">
<table>
<tr><td>Judul blok</td><td>:</td><td><input type="text" name="title" value="'.htmlentities(stripslashes(@$_POST['title'])).'" size="40" /></td></tr>
<tr><td>isi blok</td><td>:</td><td><textarea name="modul" cols="40" rows="5">'.htmlentities(stripslashes(@$data['modul'])).'</textarea></td></tr>
<tr><td>posisi</td><td>:</td><td>'.form_select('posisi',array('0'=>'kiri','1'=>'kanan')).'</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" name="submit" value="add" /></td></tr>

</table>
</form>
</fieldset>  ';
break;
case 'addmodul':
if (isset($_POST['submit'])) {
	$error = null;
	if (empty($_POST['title'])) {
		$error .= '- Error Judul modul tidak boleh kosong<br/>';
	}
	if (empty($_POST['modul'])) {
		$error .= '- Error File Modul tidak boleh kosong<br/>';
	}
	
	if ($error != '') {
		$admin .= '<div class="error">'.$error.'</div>';
	}else {
		$title = trim(strip_tags($_POST['title']));
		$modul = trim(strip_tags($_POST['modul']));
		$posisi = trim(strip_tags($_POST['posisi']));
		$cek = mysql_query("SELECT MAX(`ordering`) + 1 AS `ordering` FROM `modul` WHERE `posisi` = '$posisi'");
		$data = mysql_fetch_assoc($cek);
		$ordering = $data['ordering'];
		$insert = mysql_query("INSERT INTO `modul` (`modul`,`isi`,`posisi`,`ordering`,`type`) VALUES ('$title','$modul','$posisi','$ordering','module')");
		if ($insert) {
header("location: admin.php?pilih=admin_modul");
		exit;	
}else {
$admin .= '<div class="error">'.mysql_error().'</div>';	
}
	}
	
	
}
$admin .= '<fieldset style="padding:20px;width:80%;border: 1px solid #d1d1d1;">
<legend>Add Module</legend>
<form method="post" action="">
<table>
<tr><td>Judul modul</td><td>:</td><td><input type="text" name="title" value="'.htmlentities(stripslashes(@$_POST['title'])).'" size="40" /></td></tr>
<tr><td>File Modul (*.php)</td><td>:</td><td><input type="text" name="modul" value="'.htmlentities(stripslashes(@$_POST['modul'])).'" size="40" /></td></tr>
<tr><td>posisi</td><td>:</td><td>'.form_select('posisi',array('0'=>'kiri','1'=>'kanan')).'</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" name="submit" value="add" /></td></tr>

</table>
</form>
</fieldset>  ';
break;
case 'delete':
$id = intval($_GET['id']);
$delete = mysql_query("DELETE FROM `modul` WHERE `id` = '$id'");
if ($delete) {
header("location: admin.php?pilih=admin_modul");
		exit;	
}else {
$admin .= '<div class="error">'.mysql_error().'</div>';	
}
break;
case 'edit':
$id = intval($_GET['id']);


if (isset($_POST['submit'])) {
	$title = trim(strip_tags($_POST['title']));
	
	$cek = mysql_num_rows(mysql_query("SELECT `type` FROM `modul` WHERE `id` = '$id' AND `type` = 'module'"));
	if ($cek) {
	$modul = trim(strip_tags($_POST['modul']));
	}else {
	$modul = $_POST['modul'];	
	}
	$update = mysql_query("UPDATE `modul` SET `modul` = '$title',`isi` = '$modul' WHERE `id` = '$id'");
	if ($update) {
		header("location: admin.php?pilih=admin_modul");
		exit;
	}else {
		$admin .= '<div class="error">'.mysql_error().'</div>';
	}
}


$query = mysql_query("SELECT * FROM `modul` WHERE `id` = '$id'");
$data = mysql_fetch_assoc($query);
if ($data['type'] == 'module') {
$admin .= '<fieldset style="padding:20px;width:80%;border: 1px solid #d1d1d1;">
<legend>Edit Module</legend>
<form method="post" action="">
<table>
<tr><td>Judul modul</td><td>:</td><td><input type="text" name="title" value="'.htmlentities(stripslashes(@$data['modul'])).'" size="40" /></td></tr>
<tr><td>File Modul (*.php)</td><td>:</td><td><input type="text" name="modul" value="'.htmlentities(stripslashes(@$data['isi'])).'" size="40" /></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" name="submit" value="edit" /></td></tr>
</table>
</form>
</fieldset>  ';
}else {
$admin .= '<fieldset style="padding:20px;width:80%;border: 1px solid #d1d1d1;">
<legend>Edit block</legend>
<form method="post" action="">
<table>
<tr><td>Judul blok</td><td>:</td><td><input type="text" name="title" value="'.htmlentities(stripslashes(@$data['modul'])).'" size="40" /></td></tr>
<tr><td>isi blok</td><td>:</td><td><textarea name="modul" cols="40" rows="5">'.htmlentities(stripslashes(@$data['isi'])).'</textarea></td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" name="submit" value="edit" /></td></tr>
</table>
</form>
</fieldset>';	
	
}
break;
default:
if (isset($_POST['submit'])) {
	if (is_array($_POST['order'])) {
			foreach($_POST['order'] as $key=>$val) {
					$publish = $_POST['publish'][$key];
					$posisi = $_POST['posisi'][$key];
					$update = mysql_query("UPDATE `modul` SET `ordering` = '$val',`published` = '$publish',`posisi` = '$posisi' WHERE `id` = '$key'");
				}
		}
}
$admin .= '<h4>sidebar kiri</h4>';
$admin .= '<form method="post" action=""><table class="tabel-modul" cellspacing="0" cellpadding="0">';
$admin .= '<tr class="head"><td class="depan">title</td><td>publish</td><td>posisi</td><td>order</td><td>type</td><td>Action</td></tr>';
$query = mysql_query("SELECT * FROM `modul` WHERE `posisi` = 0 ORDER BY `ordering`");
while($data = mysql_fetch_assoc($query)) {
	$select = '<select name="publish['.$data['id'].']">';
	if ($data['published'] == 1) {
	$select .= '<option value="1" selected="selected">yes</option>';
	$select .= '<option value="0">no</option>';
	}else {
	$select .= '<option value="1">yes</option>';
	$select .= '<option value="0" selected="selected">no</option>';
	}
	
	$select .= '</select>';
	
	$select1 = '<select name="posisi['.$data['id'].']">';
	if ($data['posisi'] == 0) {
	$select1 .= '<option value="0" selected="selected">kiri</option>';
	$select1 .= '<option value="1">kanan</option>';
	}else {
	$select1 .= '<option value="0">kiri</option>';
	$select1 .= '<option value="1" selected="selected">kanan</option>';
	}
	
	$select1 .= '</select>';
	$admin .= '<tr class="isi" title="'.$data['modul'].'"><td class="depan">'.limittxt($data['modul'],15).'</td><td>'.$select.'</td><td>'.$select1.'</td><td><input type="text" name="order['.$data['id'].']" value="'.$data['ordering'].'" size="3" /></td><td>'.$data['type'].'</td><td><a href="admin.php?pilih=admin_modul&amp;action=edit&amp;id='.$data['id'].'"><img src="images/edit.gif" alt="edit"/></a> <a href="admin.php?pilih=admin_modul&amp;action=delete&amp;id='.$data['id'].'" onclick="return confirm(\'Apakah anda yakin ?\')"><img src="images/delete.gif" alt="delete"/></a></td></tr>';
	
}
$admin .= '</table>';


$admin .= '<h4>sidebar kanan</h4>';



$admin .= '<table class="tabel-modul" cellspacing="0" cellpadding="0">';
$admin .= '<tr class="head"><td class="depan">title</td><td>publish</td><td>posisi</td><td>order</td><td>type</td><td>Action</td></tr>';
$query = mysql_query("SELECT * FROM `modul` WHERE `posisi` = 1 ORDER BY `ordering`");
while($data = mysql_fetch_assoc($query)) {
	$select = '<select name="publish['.$data['id'].']">';
	if ($data['published'] == 1) {
	$select .= '<option value="1" selected="selected">yes</option>';
	$select .= '<option value="0">no</option>';
	}else {
	$select .= '<option value="1">yes</option>';
	$select .= '<option value="0" selected="selected">no</option>';
	}
	
	$select .= '</select>';
	
	$select1 = '<select name="posisi['.$data['id'].']">';
	if ($data['posisi'] == 0) {
	$select1 .= '<option value="0" selected="selected">kiri</option>';
	$select1 .= '<option value="1">kanan</option>';
	}else {
	$select1 .= '<option value="0">kiri</option>';
	$select1 .= '<option value="1" selected="selected">kanan</option>';
	}
	
	$select1 .= '</select>';
	$admin .= '<tr class="isi" title="'.$data['modul'].'"><td class="depan">'.limittxt($data['modul'],15).'</td><td>'.$select.'</td><td>'.$select1.'</td><td><input type="text" name="order['.$data['id'].']" value="'.$data['ordering'].'" size="3" /></td><td>'.$data['type'].'</td><td><a href="admin.php?pilih=admin_modul&amp;action=edit&amp;id='.$data['id'].'"><img src="images/edit.gif" alt="edit"/></a> <a href="admin.php?pilih=admin_modul&amp;action=delete&amp;id='.$data['id'].'" onclick="return confirm(\'Apakah anda yakin ?\')"><img src="images/delete.gif" alt="delete"/></a></td></tr>';
	
}
$admin .= '</table><br/><input type="submit" name="submit" value="save" /></form>';
break;
}
echo $admin;
?>