<?
if (!defined('AURACMS_admin')) {
    Header("Location: ../index.php");
    exit;
}

if (!cek_login()){
    header("location: index.php");
    exit;
} else{

	
$index_hal=1;	
	
	
$admin ='<h4 class="bg">Manage Users</h4>';
$admin .= '<div class="border"><a href="admin.php?pilih=admin_users">Home</a> | <a href="admin.php?pilih=admin_users&amp;aksi=add">Add User</a></div>';

$admin .= '<script type="text/javascript" language="javascript">
   function GP_popupConfirmMsg(msg) { //v1.0
  document.MM_returnValue = confirm(msg);
}
</script>';



if ($_GET['aksi'] == 'hapus' && is_numeric($_GET['id'])){
	$id = int_filter ($_GET['id']);
$hapus = mysql_query ("DELETE FROM `useraura` WHERE `UserId`='$id' AND `user`!='admin'");	
if ($hapus){
$admin.='<div class="sukses">Data Berhasil Dihapus Dengan ID = '.$id.'</div>';	
}else {
$admin.='<div class="error">Data Gagal dihapus Dengan ID = '.$id.'</div>';	
}	
}


if (isset ($_POST['edit_users']) && is_numeric($_GET['id'])){
	$id = int_filter ($_GET['id']);
	$level = $_POST['level'];
	$tipe = $_POST['tipe'];
	if (is_valid_email($_POST['email'])) {
	$email = $_POST['email'];
	}else {
	$email = '';	
	}
$up = mysql_query ("UPDATE `useraura` SET `level`='$level',`tipe`='$tipe',`email`='$email' WHERE `UserId`='$id' AND `user`!='admin'");	
if ($up){
$admin.='<div class="sukses">Data Berhasil Diupdate Dengan ID = '.$id.'</div>';	
}else {
$admin.='<div class="error">Data Gagal diupdate Dengan ID = '.$id.'</div>';	
}	
}



if ($_GET['aksi'] == 'add'){
	
	
if (isset($_POST['add_users'])){
	
$user = cleantext($_POST['user']);	
$level = cleantext($_POST['level']);	
$tipe = cleantext($_POST['tipe']);
$password = cleantext($_POST['password']);
$email = cleantext($_POST['email']);

if(!empty($user) && !empty($email) && !empty($password)){

$ceks = mysql_num_rows(mysql_query("SELECT `user` FROM `useraura` WHERE `user` = '$user'"));

if ($ceks >= 1){
	$admin .= '<div class="border">Error Username udah ada yg pake</div>';
}else {
$query = mysql_query ("INSERT INTO `useraura` (`user`,`password`,`level`,`tipe`,`email`) VALUES ('$user',md5('$password'),'$level','$tipe','$email')");	
if ($query){
	$admin .= '<div class="sukses">Data Berhasil Di add</div>';
}
}
	
} else {
	$admin .= '<div class="error">Silahkan Isi Dengan Benar</div>';
}	
	
}	
	
	

$ss = mysql_query ("SHOW FIELDS FROM useraura");
while ($as = mysql_fetch_array ($ss)){
	 $arrs = $as['Type'];
	
	if (substr($arrs,0,4) == 'enum' && $as['Field'] == 'level') break;
}

if (isset ($_GET['offset']) && isset ($_GET['pg']) && isset ($_GET['stg'])) {
$qss = "&pg=$pg&stg=$stg&offset=$offset";
}	
$admin.='<div class="border">';



$admin .= "<form method='post' action='#'>
<table cellspacing=\"3\" cellpadding=\"1\" style='width:100%'>
  <tr>
    <td width='30%' valign='top'>User </td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'><input type='text' name='user' size='20' /></td>
  </tr> 
  <tr>
    <td width='30%' valign='top'>Password </td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'><input type='text' name='password' size='20' /></td>
  </tr>
  <tr>
    <td width='30%' valign='top'>Email </td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'><input type='text' name='email' size='20' /></td>
  </tr>";
  
  
$sel = '<select name="level">';
$arrs = ''.substr ($arrs,4);
$arr = eval( '$arr5 = array'.$arrs.';' );
foreach ($arr5 as $k=>$v){
	$sel .= '<option value="'.$v.'">'.$v.'</option>';	
	
}

$sel .= '</select>';  
  
$sel2 = '<select name="tipe">';
$arr2 = array ('aktif','pasif');
foreach ($arr2 as $kk=>$vv){
	$sel2 .= '<option value="'.$vv.'">'.$vv.'</option>';	

}

$sel2 .= '</select>';    
  
  
$admin .= "<tr>
    <td width='30%' valign='top'>Level </td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'>$sel</td>
  </tr>";

$admin .= "<tr>
    <td width='30%' valign='top'>Status</td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'>$sel2</td>
  </tr>";  
  
  

$admin .= "<tr><td width='30%'>&nbsp;</td>
    <td width='1%'>&nbsp;</td>
    <td width='69%'><br /><input type='submit' value='Add' name='add_users' /></td>
  </tr>
</table></form>";
$admin .= '</div>';		
	
	
	
}

if ($_GET['aksi'] == 'edit'){
global $qss;
	$id = int_filter($_GET['id']);
$s = mysql_query ("SELECT * FROM `useraura` WHERE `UserId`='$id'");	
$data = mysql_fetch_array($s);
$user = $data['user'];	
$level = $data['level'];	
$tipe = $data['tipe'];
$email = $data['email'];
$ss = mysql_query ("SHOW FIELDS FROM useraura");
while ($as = mysql_fetch_array ($ss)){
	 $arrs = $as['Type'];
	
	if (substr($arrs,0,4) == 'enum' && $as['Field'] == 'level') break;
}

if (isset ($_GET['offset']) && isset ($_GET['pg']) && isset ($_GET['stg'])) {
$qss = "&amp;pg=$pg&amp;stg=$stg&amp;offset=$offset";
}	
$admin.='<div class="border">';



$admin .= "<form method='post' action='admin.php?pilih=admin_users&amp;id=$id$qss'>
<table style='width:100%;border:0' cellpadding='2'>
  <tr>
    <td width='30%' valign='top'>User </td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'><input type='text' name='user' size='20' value='$user' disabled='disabled' /></td>
  </tr>";
$admin .= "<tr>
    <td width='30%' valign='top'>Email </td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'><input type='text' name='email' size='20' value='$email' /></td>
  </tr>";  
  
$sel = '<select name="level">';
$arrs = ''.substr ($arrs,4);
$arr = eval( '$arr5 = array'.$arrs.';' );
foreach ($arr5 as $k=>$v){
	if ($level == $v){
	$sel .= '<option value="'.$v.'" selected="selected">'.$v.'</option>';
	}else {
	$sel .= '<option value="'.$v.'">'.$v.'</option>';	
	}
}

$sel .= '</select>';  
  
$sel2 = '<select name="tipe">';
$arr2 = array ('aktif','pasif');
foreach ($arr2 as $kk=>$vv){
	if ($tipe == $vv){
	$sel2 .= '<option value="'.$vv.'" selected="selected">'.$vv.'</option>';
	}else {
	$sel2 .= '<option value="'.$vv.'">'.$vv.'</option>';	
	}
}

$sel2 .= '</select>';    
  
  
$admin .= "<tr>
    <td width='30%' valign='top'>Level </td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'>$sel</td>
  </tr>";

$admin .= "<tr>
    <td width='30%' valign='top'>Status</td>
    <td width='1%' valign='top'>:</td>
    <td width='69%' valign='top'>$sel2</td>
  </tr>";  
  
  

$admin .= "<tr><td width='30%'>&nbsp;</td>
    <td width='1%'>&nbsp;</td>
    <td width='69%'><br /><input type='submit' value='Edit' name='edit_users' /></td>
  </tr>
</table></form>";
$admin .= '</div>';		
}




if (!in_array($_GET['aksi'],array('add','edit'))){
$admin.='
<table style="width:100%" cellpadding="0" bgcolor="#d5d5d5">
  <tr>
    <td><b>Users</b></td>
    <td align="center"><b>Email</b></td>
    <td align="center"><b>Level</b></td>
    <td align="center"><b>Status</b></td>
    <td align="center" colspan="2"><b>Actions</b></td>
  </tr>';


$limit = 40;
$qss = null;
$offset = int_filter(@$_GET['offset']);
$pg		= int_filter(@$_GET['pg']);
$stg	= int_filter(@$_GET['stg']);

$query = $koneksi_db->sql_query("SELECT count(user) as t FROM `useraura`");
$rows = mysql_fetch_row ($query);
$jumlah = $rows[0];
mysql_free_result ($query);
$a = new paging ($limit);
if ($jumlah > 0){

$q = mysql_query ("SELECT * FROM `useraura` LIMIT $offset,$limit");
if (isset ($_GET['offset']) && isset ($_GET['pg']) && isset ($_GET['stg'])) {
$qss = "&pg=$pg&stg=$stg&offset=$offset";
}


while ($data = mysql_fetch_array($q)){
	
	$admin.='
  <tr>
    <td bgcolor="#FFFFFF">'.$data['user'].'</td>
    <td bgcolor="#FFFFFF" align="center">'.$data['email'].'</td>
    <td bgcolor="#FFFFFF" align="center">'.$data['level'].'</td>
    <td bgcolor="#FFFFFF" align="center">'.$data['tipe'].'</td>
    <td bgcolor="#FFFFFF" align="center"><a href="?pilih=admin_users&amp;aksi=edit&amp;id='.$data['UserId'].$qss.'"><img border="0" src="images/edit.gif" width="24" height="15" alt="edit" /></a>
     <a href="?pilih=admin_users&amp;aksi=hapus&amp;id='.$data['UserId'].$qss.'" onclick="GP_popupConfirmMsg(\'Apakah anda Ingin menghapus Users \n['.$data['user'].']\');return document.MM_returnValue;"><img border="0" src="images/delete_button.gif" width="22" height="15" alt="del" /></a></td>
  </tr>';  
}

}


$admin .= '</table>';

if($jumlah>$limit){

if (empty($_GET['offset']) and !isset ($_GET['offset'])) {
$offset = 0;

}

if (empty($_GET['pg']) and !isset ($_GET['pg'])) {
$pg = 1;
}

if (empty($_GET['stg']) and !isset ($_GET['stg'])) {
$stg = 1;
}
$admin .='<div class="border">';
$admin .="<center>";
$admin .= $a-> getPaging($jumlah, $pg, $stg);
$admin .="</center>";
$admin .='</div>';

}



}

}

echo $admin;
?>
