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

$admin ='<h4 class="bg">Modul Actions</h4>';
$admin .='<div class="border"><a href="?pilih=admin_actions"><b>Home</b></a> | <a href="?pilih=admin_actions&amp;action=addmodul"><b>Buat action Modul Baru</b></a></div>';

switch(@$_GET['action']) {
case 'delete_action':
$modul = mysql_escape_string($_GET['modul']);
$query = mysql_query("DELETE FROM `actions` WHERE `modul` = '$modul'");
if ($query) {
	header("location: admin.php?pilih=admin_actions");
	exit;
}else {
	$admin .= '<div class="error">'.mysql_error().'</div>';
}
break;
case 'addmodul':

if (isset($_POST['submit'])) {

	$modul_id = intval($_POST['modul_id']);
	$modul = mysql_escape_string($_POST['modul']);
	$posisi = intval($_POST['posisi']);
	
	
	
		$error = null;
	
	if (empty($_POST['modul'])) {
		$error .= '- Nama modul tidak boleh kosong<br/>';
		}
	if (!file_exists('mod/'.$_POST['modul'])) {
		$error .= '- path mod/'.$_POST['modul'].' tidak ada<br/>';
		}

	$cek1 = mysql_num_rows(mysql_query("SELECT `id` FROM `actions` WHERE `modul_id` = '$modul_id' AND `modul` = '$modul'"));
	if ($cek1) {
		$error .= '- id modul/blok sudah ada<br/>';
	}
		
		if ($error != '') {
			$admin .= '<div class="error">'.$error.'</div>';
		}else {
			$cek2 = mysql_query("SELECT (MAX(`order`) + 1) AS `order` FROM `actions` WHERE `posisi` = '$posisi' AND `modul` = '$modul'");
			$data = mysql_fetch_assoc($cek2);
			$order = $data['order'];
			$inserts = mysql_query("INSERT INTO `actions` (`modul`,`posisi`,`order`,`modul_id`) VALUES ('$modul','$posisi','$order','$modul_id')");
			if ($inserts) {
				$admin .= '<div class="sukses">Sukses add data</div>';
			}else {
				$admin .= '<div class="error">'.mysql_error().'</div>';
			}
		}
		
}



$handler = array();
$query = mysql_query("SELECT * FROM `modul` ORDER BY `ordering`");
while($data = mysql_fetch_assoc($query)) {
	$publish = $data['published'] ? 'publish' : 'no publish';
$handler[$data['id']] = $data['modul'] . ' - ' . $publish;	
}





$admin .= '<fieldset style="padding:20px;width:80%;border: 1px solid #d1d1d1;">
<legend>Add block</legend>
<form method="post" action="">
<table>
<tr><td>Nama modul</td><td>:</td><td><input type="text" name="modul" value="'.htmlentities(stripslashes(@$_POST['modul'])).'" size="40" /><br/>contoh : news</td></tr>
<tr><td>Pilih blok/modul</td><td>:</td><td>'.form_select('modul_id',$handler).'</td></tr>
<tr><td>posisi</td><td>:</td><td>'.form_select('posisi',array('0'=>'kiri','1'=>'kanan')).'</td></tr>
<tr><td>&nbsp;</td><td>&nbsp;</td><td><input type="submit" name="submit" value="add" /></td></tr>

</table>
</form>
</fieldset>
<blockquote>
untuk nama modul di dapat dari action ,<br/> misal nya :<br/>
index.php?pilih=news&amp;mod=yes<br/>
Berarti nama modul nya : <b>news</b>

</blockquote>
  ';

break;	
	
		
case 'view':
$modul = mysql_escape_string(strip_tags($_GET['modul']));
$admin .= '<h2>'.$modul.'</h2>';

if (isset($_GET['delete'])) {
	$id = intval($_GET['id']);
	$del = mysql_query("DELETE FROM `actions` WHERE `id` = '$id'");
}


if (isset($_POST['submit'])) {
	if (is_array($_POST['order'])) {
			foreach($_POST['order'] as $key=>$val) {
					$posisi = $_POST['posisi'][$key];
					$order = $_POST['order'][$key];
					$update = mysql_query("UPDATE `actions` SET `posisi` = '$posisi',`order` = '$order' WHERE `id` = '$key'");
				}
		}
}




$admin .= '<b>leftside</b>';
$admin .= '<form method="post" action=""><table class="tabel-modul" cellspacing="0" cellpadding="0">';
$admin .= '<tr class="head"><td class="depan">block</td><td>posisi</td><td>order</td><td>action</td></tr>';
$query = mysql_query("SELECT `actions`.*,`modul`.`modul` FROM `actions` LEFT JOIN `modul` ON (`modul`.`id` = `actions`.`modul_id`) WHERE `actions`.`modul` = '$modul' AND `actions`.`posisi` = 0 ORDER BY `actions`.`order`");
while($data = mysql_fetch_assoc($query)) {
	
	
	$select1 = '<select name="posisi['.$data['id'].']">';
	if ($data['posisi'] == 0) {
	$select1 .= '<option value="0" selected="selected">kiri</option>';
	$select1 .= '<option value="1">kanan</option>';
	}else {
	$select1 .= '<option value="0">kiri</option>';
	$select1 .= '<option value="1" selected="selected">kanan</option>';
	}
	$select1 .= '</select>';
	
	
$admin .= '<tr class="isi"><td class="depan">'.$data['modul'].'</td><td>'.$select1.'</td><td><input type="text" name="order['.$data['id'].']" value="'.$data['order'].'" size="3" /></td><td><a href="admin.php?pilih=admin_actions&amp;action=view&amp;modul='.$modul.'&amp;id='.$data['id'].'&amp;delete=1" onclick="return confirm(\'Apakah anda yakin ?\')"><img src="images/delete.gif" alt="delete"/></a></td></tr>';
}
$admin .= '</table>';






$admin .= '<p>&nbsp;</p><b>rightside</b>';
$admin .= '<table class="tabel-modul" cellspacing="0" cellpadding="0">';
$admin .= '<tr class="head"><td class="depan">block</td><td>posisi</td><td>order</td><td>action</td></tr>';
$query = mysql_query("SELECT `actions`.*,`modul`.`modul` FROM `actions` LEFT JOIN `modul` ON (`modul`.`id` = `actions`.`modul_id`) WHERE `actions`.`modul` = '$modul' AND `actions`.`posisi` = 1 ORDER BY `actions`.`order`");
while($data = mysql_fetch_assoc($query)) {
	
	
	$select1 = '<select name="posisi['.$data['id'].']">';
	if ($data['posisi'] == 0) {
	$select1 .= '<option value="0" selected="selected">kiri</option>';
	$select1 .= '<option value="1">kanan</option>';
	}else {
	$select1 .= '<option value="0">kiri</option>';
	$select1 .= '<option value="1" selected="selected">kanan</option>';
	}
	$select1 .= '</select>';
	
	
$admin .= '<tr class="isi"><td class="depan">'.$data['modul'].'</td><td>'.$select1.'</td><td><input type="text" name="order['.$data['id'].']" value="'.$data['order'].'" size="3" /></td><td><a href="admin.php?pilih=admin_actions&amp;action=view&amp;modul='.$modul.'&amp;id='.$data['id'].'&amp;delete=1" onclick="return confirm(\'Apakah anda yakin ?\')"><img src="images/delete.gif" alt="delete"/></a></td></tr>';
}
$admin .= '</table><br/><input type="submit" name="submit" value="save" /></form>';

break;	
	
	
	
default:
$admin .= '<table class="tabel-modul" cellspacing="0" cellpadding="0">';
$admin .= '<tr class="head"><td class="depan">Modul action</td><td>View</td><td>delete</td></tr>';
$query = mysql_query("SELECT * FROM `actions` GROUP BY `modul`");
while($data = mysql_fetch_assoc($query)) {
$admin .= '<tr class="isi"><td class="depan">'.$data['modul'].'</td><td><a href="admin.php?pilih=admin_actions&amp;action=view&amp;modul='.$data['modul'].'">View</a></td><td><a href="admin.php?pilih=admin_actions&amp;action=delete_action&amp;modul='.$data['modul'].'" onclick="return confirm(\'Apakah anda yakin ?\')"><img src="images/delete.gif" alt="delete"/></a></td></tr>';
}
$admin .= '</table>';
break;
}
echo $admin;
?>