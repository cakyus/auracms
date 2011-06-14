<?
if (!defined('AURACMS_admin')) {
	Header("Location: ../index.php");
	exit;
}

$index_hal = 1;
$admin = '';
if (!cek_login ()){   
	
$admin .='<p class="judul">Access Denied !!!!!!</p>';
}else{
	

$admin .='<h4 class="bg">Menu Manager</h4>';

$admin .='<div class="border"><a href="?pilih=admin_menu"><b>Home</b></a> | <a href="?pilih=admin_menu&amp;aksi=add"><b>Buat Menu Baru</b></a> | <a href="?pilih=admin_menu&amp;aksi=addsub"><b>Buat Sub Menu Baru</b></a> | <a href="?pilih=admin_menu&amp;aksi=menuuser"><b>Menu Users</b></a> | <a href="?pilih=admin_menu&amp;aksi=menuadmin"><b>Menu Admin</b></a></div>';


if($_GET['aksi']=="up"){

$ID = int_filter ($_GET['id']);
$parent = int_filter ($_GET['parent']);
$select = mysql_query ("SELECT MAX(ordering) as sc FROM submenu WHERE parent='$parent'");
$data = mysql_fetch_array ($select);

if ($data['sc'] <= 0){
$qquery = mysql_query ("SELECT `id` FROM `submenu` WHERE parent='$parent'");
$integer = 1;
while ($getsql = mysql_fetch_assoc($qquery)){
mysql_query ("UPDATE `submenu` SET `ordering` = $integer WHERE `id` = '".$getsql['id']."'");
$integer++;	
}	
header ("location:admin.php?pilih=admin_menu");
exit;	
}

$total = $data['sc'] + 1;
$update = mysql_query ("UPDATE submenu SET ordering='$total' WHERE ordering='".($ID-1)."' AND parent='$parent'"); 
$update = mysql_query ("UPDATE submenu SET ordering=ordering-1 WHERE ordering='$ID' AND parent='$parent'");
$update = mysql_query ("UPDATE submenu SET ordering='$ID' WHERE ordering='$total' AND parent='$parent'");   
header ("location:admin.php?pilih=admin_menu");
}

if($_GET['aksi']=="down"){
$ID = int_filter ($_GET['id']);
$parent = int_filter ($_GET['parent']);
$select = mysql_query ("SELECT MAX(ordering) as sc FROM submenu WHERE parent='$parent'");
$data = mysql_fetch_array ($select);

if ($data['sc'] <= 0){
$qquery = mysql_query ("SELECT `id` FROM `submenu` WHERE parent='$parent'");
$integer = 1;
while ($getsql = mysql_fetch_assoc($qquery)){
mysql_query ("UPDATE `submenu` SET `ordering` = $integer WHERE `id` = '".$getsql['id']."'");
$integer++;	
}	
	
header ("location:admin.php?pilih=admin_menu");
exit;	
}

$total = $data['sc'] + 1;
$update = mysql_query ("UPDATE submenu SET ordering='$total' WHERE ordering='".($ID+1)."' AND parent='$parent'"); 
$update = mysql_query ("UPDATE submenu SET ordering=ordering+1 WHERE ordering='$ID' AND parent='$parent'");
$update = mysql_query ("UPDATE submenu SET ordering='$ID' WHERE ordering='$total' AND parent='$parent'");

header ("location:admin.php?pilih=admin_menu");
}




if($_GET['aksi']=="mup"){

$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM menu");
$data = $koneksi_db->sql_fetchrow ($select);

if ($data['sc'] <= 0){
$qquery = mysql_query ("SELECT `id` FROM `submenu`");
$integer = 1;
while ($getsql = mysql_fetch_assoc($qquery)){
mysql_query ("UPDATE `menu` SET `ordering` = $integer WHERE `id` = '".$getsql['id']."'");
$integer++;	
}	
	
header ("location:".$adminfile.".php?pilih=admin_menu");
exit;	
}

$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE menu SET ordering='$total' WHERE ordering='".($ID-1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE menu SET ordering=ordering-1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE menu SET ordering='$ID' WHERE ordering='$total'");   
header ("location:".$adminfile.".php?pilih=admin_menu");
}

if($_GET['aksi']=="mdown"){
$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM menu");
$data = $koneksi_db->sql_fetchrow ($select);

if ($data['sc'] <= 0){
$qquery = mysql_query ("SELECT `id` FROM `menu`");
$integer = 1;
while ($getsql = mysql_fetch_assoc($qquery)){
mysql_query ("UPDATE `menu` SET `ordering` = $integer WHERE `id` = '".$getsql['id']."'");
$integer++;	
}	
	
header ("location:".$adminfile.".php?pilih=admin_menu");
exit;	
}

$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE menu SET ordering='$total' WHERE ordering='".($ID+1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE menu SET ordering=ordering+1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE menu SET ordering='$ID' WHERE ordering='$total'");

header ("location:".$adminfile.".php?pilih=admin_menu");
}





if($_GET['aksi']==""){
	
$hasil = $koneksi_db->sql_query( "SELECT * FROM menu ORDER BY ordering" );

$querymax = mysql_query ("SELECT MAX(`ordering`) FROM `menu`");
$alhasil = mysql_fetch_row($querymax);	
$numbers_parent = $alhasil[0];

$admin .='
<table cellspacing="0" style="width:100%">
	<tr bgcolor="#d1d1d1">
	<th style="width:25px;text-align:center;padding:10px 5px 10px 5px;border-left:1px solid #ccc;border-top:1px solid #ccc;">No</th>
	<th style="text-align:left;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Menu Item</th>
	<th style="text-align:center;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Order</th>
	<th style="text-align:center;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Publikasi</th>
	<th colspan="2" style="text-align:center;padding:10px 5px 10px0 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;width:160px;">Aksi</th>
	</tr>';
$no = 1;
while ($data = $koneksi_db->sql_fetchrow($hasil)) {
	$parent=$data[0];
	$published = ($data['published'] == 1) ? '<a href="?pilih=admin_menu&amp;aksi=pub&amp;pub=tidak&amp;id='.$parent.'"><img src="images/ya.png" border="0" alt="ya" /></a>' : '<a href="?pilih=admin_menu&amp;aksi=pub&amp;pub=ya&amp;id='.$parent.'"><img src="images/tidak.png" border="0" alt="no" /></a>';
	
$orderd = '<a href="'.$adminfile.'.php?pilih=admin_menu&amp;aksi=mdown&amp;id='.$data[4].'"><img src="images/downarrow-1.png" border="0" alt="down" /></a>';    
$orderu = '<a href="'.$adminfile.'.php?pilih=admin_menu&amp;aksi=mup&amp;id='.$data[4].'"><img src="images/uparrow-1.png" border="0" alt="up" /></a>'; 

   
$ordering_down = $orderd;    
$ordering_up = $orderu;        

if ($data[4] == 1) $ordering_up = '&nbsp;&nbsp;&nbsp;';
if ($data[4] == $numbers_parent) $ordering_down = '&nbsp;';		

$admin .='
	<tr>
	<td style="width:25px;text-align:center;padding:2px;border-left:1px solid #ccc;border-top:1px solid #ccc;"><b>'.$no.'</b></td>
	<td style="text-align:left;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;"><a href="'.$data['url'].'"><b>'.$data['menu'].'</b></a></td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$ordering_up.'  '.$ordering_down.'</td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$published.'</td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;width:30px;"><a href="?pilih=admin_menu&amp;aksi=del&amp;id='.$data['id'].'" onclick="return confirm(\'Apakah Anda Yakin Ingin Menghapus Data Ini ?\')"><img border="0" src="images/delete_button.gif" width="22" height="15" alt="del" /></a></td>
	<td style="text-align:center;padding:2px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;width:30px;"><a href="?pilih=admin_menu&amp;aksi=edit&amp;id='.$data[0].'"><img border="0" src="images/edit.gif" width="24" height="15" alt="edit" /></a></td>
	</tr>';


$subhasil = $koneksi_db->sql_query( "SELECT * FROM submenu WHERE parent='$parent' ORDER BY ordering ");		
$jmlsub = $koneksi_db->sql_numrows( $subhasil );	
$querymax = mysql_query ("SELECT MAX(`ordering`) FROM `submenu` WHERE parent=$parent");
$alhasil = mysql_fetch_row($querymax);	
$numbers = $alhasil[0];
if ($jmlsub>0) {
$warna = '';		
$i = 1;
while ($subdata = $koneksi_db->sql_fetchrow($subhasil)) {            
$spublished = ($subdata['published'] == 1) ? '<a href="?pilih=admin_menu&amp;aksi=spub&amp;pub=tidak&amp;id='.$subdata[0].'"><img src="images/ya.png" border="0" alt="ya" /></a>' : '<a href="?pilih=admin_menu&amp;aksi=spub&amp;pub=ya&amp;id='.$subdata[0].'"><img src="images/tidak.png" border="0" alt="no" /></a>';
$orderd = '<a href="'.$adminfile.'.php?pilih=admin_menu&amp;aksi=down&amp;id='.$subdata[5].'&amp;parent='.$parent.'"><img src="images/downarrow.png" border="0" alt="down" /></a>';    
$orderu = '<a href="'.$adminfile.'.php?pilih=admin_menu&amp;aksi=up&amp;id='.$subdata[5].'&amp;parent='.$parent.'"><img src="images/uparrow.png" border="0" alt="up" /></a>'; 
$ordering_down = $orderd;    
$ordering_up = $orderu;        

if ($subdata[5] == 1) $ordering_up = '';
if ($subdata[5] == $numbers) $ordering_down = '';			
						
$warna = empty ($warna) ? ' bgcolor="#efefef"' : '';

$admin .='
	<tr>
	<td style="width:25px;text-align:center;padding:2px;border-left:1px solid #ccc;border-top:1px solid #ccc;"></td>
	<td style="text-align:left;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;"><img src="images/1.gif" alt="" border="" /> <a href="'.$subdata['url'].'">'.$subdata['menu'].'</a></td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$ordering_up.'  '.$ordering_down.'</td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$spublished.'</td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;width:30px;"><a href="?pilih=admin_menu&amp;aksi=delsub&amp;id='.$subdata['id'].'" onclick="return confirm(\'Apakah Anda Yakin Ingin Menghapus Data Ini ?\')"><img border="0" src="images/delete_button.gif" width="22" height="15" alt="del" /></td>
	<td style="text-align:center;padding:2px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;width:30px;"><a href="?pilih=admin_menu&amp;aksi=editsub&amp;id='.$subdata[0].'"><img border="0" src="images/edit.gif" width="24" height="15" alt="edit" /></a></td>
	</tr>';
$i++;		
}		
}
unset($numbers);
$no++;
}
$admin .= '<tr><td colspan="6" style="width:25px;text-align:center;padding:5px;border-top:1px solid #ccc;">&nbsp;</td></tr></table>';
}

if($_GET['aksi']=="add"){
	global $koneksi_db, $theme,$error;
	
if (isset($_POST['submit'])) {
	$menu     = $_POST['menu'];
	$url      = $_POST['url'];
	$error = '';
	if (!$menu)  $error .= "Error: Silahkan Masukkan Nama Menunya!<br />";
	if (!$url) $error .= "Error: Silahkan Masukkan Url Menunya!<br />";
	if ($error){
		$admin.='<div class="error">'.$error.'</div>';
	}else {
		$url = str_replace('&amp;','&',$url);
		$url = str_replace('&','&amp;',$url);
		
	$cekmax = mysql_query ("SELECT (MAX(`ordering`)+1) FROM `menu`");
	$getcekmax = mysql_fetch_row($cekmax);
	$hasil = $koneksi_db->sql_query( "INSERT INTO menu (menu,url,ordering) VALUES ('$menu','$url','".$getcekmax[0]."')" );
	if($hasil){
		$admin.='<div class="sukses">Menu Baru Berhasil dibuat</div>';
		$style_include[] ='<meta http-equiv="refresh" content="1; url=?pilih=admin_menu" />';
		}		
	}
}
	
	
$url = isset($_POST['submit']) ? $_POST['url'] : @$_GET['url'];
	
$admin .='<div class="border">';	
$admin .='<form method="post" action="">    
<table>        
<tr>            
<td>Menu</td>            
<td valign="top">:</td>            
<td><input type="text" size="30" name="menu" /></td>        
</tr>        
<tr>            
<td valign="top">URL</td>            
<td valign="top">:</td>            
<td><input type="text" size="30" name="url" value="'.$url.'" /><br /> contoh : <b>http://google.com</b></td>        
</tr>        
<tr>            
<td></td><td></td><td colspan="2"><input type="submit" name="submit" value="Buat" /></td>        
</tr>    
</table></form>';
$admin .='</div>';

}

if($_GET['aksi']=="addsub"){
	global $koneksi_db, $theme;
	
if (isset($_POST['submit'])) {
	$menu     = $_POST['menu'];
	$url      = $_POST['url'];
	$parent1  = $_POST['parent1'];
	$error = '';
	if (!$menu)  $error .= "Error: Silahkan Masukkan Nama Menunya!<br />";
	if (!$url) $error .= "Error: Silahkan Masukkan Url Menunya!<br />";
	if ($error){
		$admin.='<div class="error">'.$error.'</div>';
	}else {
		$url = str_replace('&amp;','&',$url);
		$url = str_replace('&','&amp;',$url);
	$cekmax = mysql_query ("SELECT (MAX(`ordering`)+1) FROM `submenu` WHERE `parent` = '$parent1'");
	$cekmaxnum = mysql_fetch_row($cekmax);
	$hasil = $koneksi_db->sql_query( "INSERT INTO submenu (menu,url,parent,ordering) VALUES ('$menu','$url','$parent1','".$cekmaxnum[0]."')" );
	if($hasil){
		$admin.='<div class="sukses">Sub Menu baru telah dibuat.</div>';
		$style_include[] ='<meta http-equiv="refresh" content="1; url=?pilih=admin_menu" />';
	}		
	}
}
$url = isset($_POST['submit']) ? $_POST['url'] : @$_GET['url'];

$admin .='<div class="border">';
$admin .='<form method="post" action="">    
<table>        
<tr>            
<td>Menu</td>            
<td>:</td>            
<td><input type="text" size="30" name="menu" /></td>        
</tr>        
<tr>            
<td valign="top">URL</td><td>:</td><td><input type="text" size="30" name="url" value="'.$url.'" /><br /> contoh : <b>http://www.google.com</b></td>        
</tr>        
<tr>            
<td valign="top">Sub dari</td>            
<td>:</td>            
<td>';            

$hasil = $koneksi_db->sql_query( "SELECT * FROM menu ORDER BY id" );            

$admin .='<select name="parent1">';            
while ($data = $koneksi_db->sql_fetchrow($hasil)) {       	
	$admin .='<option value="'.$data[0].'">'.$data[1].'</option>';            
}

$admin .='</select>';
$admin .='        
</td>        
</tr>        
<tr>            
<td></td><td></td><td colspan="2"><input type="submit" name="submit" value="Buat" /></td>        
</tr>    
</table></form>';
$admin .='</div>';
}

if($_GET['aksi']=="del"){    
	global $koneksi_db;    
	$id     = int_filter($_GET['id']);    
	
	$hasil = $koneksi_db->sql_query("DELETE FROM menu WHERE id='$id'");    
	if($hasil){    
		$admin.='<div class="sukses">Menu telah di delete! .</div>';    
		$style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_menu" />';    
	}
}

if($_GET['aksi']=="delsub"){    
	global $koneksi_db;    
	$id     = int_filter($_GET['id']);    
	$hasil = $koneksi_db->sql_query("DELETE FROM submenu WHERE id='$id'");    
	if($hasil){    
		$admin.='<div class="sukses">Sub Menu telah di delete! .</div>';    
		$style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_menu" />';    
	}
}

if($_GET['aksi']=="edit"){
	global $koneksi_db,$error;
	$id     = int_filter($_GET['id']);
	
if (isset($_POST['submit'])) {
	$menu     = $_POST['menu'];
	$url      = $_POST['url'];
	
	if (!$menu)  $error .= "Error: Silahkan Masukkan Nama Menunya!<br />";
	if (!$url) $error .= "Error: Silahkan Masukkan Url Menunya!<br />";
	
	if ($error){
		$admin.='<div class="error>'.$error.'</div>';
	}else{
		
		$url = str_replace('&amp;','&',$url);
		$url = str_replace('&','&amp;',$url);
	
	$hasil = $koneksi_db->sql_query( "UPDATE menu SET menu='$menu', url='$url' WHERE id='$id'" );
	if($hasil){
		$admin.='<div class="sukses">Menu telah di updated</div>';
		$style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_menu">';
	}
       }
}else{
	$hasil = $koneksi_db->sql_query( "SELECT * FROM menu WHERE id=$id" );
	while ($data = $koneksi_db->sql_fetchrow($hasil)) {    
		
		$id=$data[0];    
		$menu=$data[1];    
		$url=$data[2];    
	}
$admin .='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="3" class="bodyline"><tr><td class="bgcolor1">';
$admin .='<b>Edit Menu Item</b><form method="post" action="">    
<table>        
<tr>            
<td>Menu</td>            
<td>:</td>            
<td><input type="hidden" name="id" value="'.$id.'"><input type="text" size="30" name="menu" value="'.$menu.'"></td>        
</tr>        
<tr>            
<td>URL</td>            
<td>:</td>            
<td><input type="text" size="30" name="url" value="'.$url.'"></td>        
</tr>        
<tr>            
<td></td><td></td><td colspan="2"><input type="submit" name="submit" value="Update"></td>        
</tr>    
</table></form>';
$admin .='</td></tr></table></td></tr></table>';

}
}

if($_GET['aksi']=="editsub"){
	global $koneksi_db,$error;
	$id     = int_filter($_GET['id']);
	
if (isset($_POST['submit'])) {
	$menu     = $_POST['menu'];
	$url      = $_POST['url'];
	$parent1   = $_POST['parent1'];
	
if (!$menu)  $error .= "Error: Silahkan Masukkan Nama Menunya!<br />";
if (!$url) $error .= "Error: Silahkan Masukkan Url Menunya!<br />";
if (!$parent1) $error .= "Error: Silahkan Pilih Parent untuk  Sub Menunya!<br />";
if ($error){
	$admin.='<div class="error">'.$error.'</div>';
}else{
$url = str_replace('&amp;','&',$url);
		$url = str_replace('&','&amp;',$url);
$hasil = $koneksi_db->sql_query( "UPDATE submenu SET menu='$menu', url='$url', parent='$parent1' WHERE id='$id'");
if($hasil){
	$admin.='<div class="sukses">Sub Menu telah di updated</div>';
	$style_include[] ='<meta http-equiv="refresh" content="3; url=?pilih=admin_menu" />';
}
}
}else{

	$hasil = $koneksi_db->sql_query( "SELECT * FROM submenu WHERE id=$id" );
	while ($data = $koneksi_db->sql_fetchrow($hasil)) {    
		$id=$data[0];    
		$menu=$data[1];    
		$url=$data[2];    
		$parent=$data[3];
	}

$admin .='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="3" class="bodyline"><tr><td class="bgcolor1">';
$admin .='<b>Edit Menu Item</b>';
$admin .='<form method="post" action="" >    
<table>        
<tr>            
<td>Menu</td>            
<td>:</td>            
<td><input type="hidden" name="id" value="'.$id.'"><input type="text" size="30" name="menu" value="'.$menu.'"></td>        
</tr>        
<tr>            
<td>URL</td>            
<td>:</td>            
<td><input type="text" size="30" name="url" value="'.$url.'"></td>        
</tr>        
<tr>            
<td valign="top">Sub dari</td>            
<td>:</td>            
<td><select name="parent1">';
$hasil = $koneksi_db->sql_query( "SELECT * FROM menu ORDER BY id" );
while ($data = $koneksi_db->sql_fetchrow($hasil)) {
	$pilihan = ($data[0]==$parent)?"selected":'';
	$admin .='<option value="'.$data['0'].'" '.$pilihan.'>'.$data[1].'</option>';
}

$admin .='</select>        
</td>        
</tr>        
<tr>            
<td></td><td></td><td colspan="2"><input type="submit" name="submit" value="Update"></td>        
</tr>    
</table></form> ';
$admin .='</td></tr></table></td></tr></table>';


}
}

if ($_GET['aksi'] == 'pub'){	
	if ($_GET['pub'] == 'tidak'){	
		$id = int_filter ($_GET['id']);	
		$koneksi_db->sql_query ("UPDATE menu SET published=0 WHERE id='$id'");		
	}	
	
	if ($_GET['pub'] == 'ya'){	
		$id = int_filter ($_GET['id']);	
		$koneksi_db->sql_query ("UPDATE menu SET published=1 WHERE id='$id'");		
	}	
	header ("location:?pilih=admin_menu");
	exit;
}

if ($_GET['aksi'] == 'spub'){	
	if ($_GET['pub'] == 'tidak'){	
		$id = int_filter ($_GET['id']);	
		$koneksi_db->sql_query ("UPDATE submenu SET published=0 WHERE id='$id'");		
	}	
	if ($_GET['pub'] == 'ya'){	
		$id = int_filter ($_GET['id']);	
		$koneksi_db->sql_query ("UPDATE submenu SET published=1 WHERE id='$id'");		
	}	
	header ("location:?pilih=admin_menu");
	exit;
}
}

if($_GET['aksi']== 'delma'){    
	global $koneksi_db;    
	$id     = int_filter($_GET['id']);    
	$hasil = $koneksi_db->sql_query("DELETE FROM `admin` WHERE `id`='$id'");    
	if($hasil){    
		$admin.='<div class="sukses">Menu Admin berhasil dihapus! .</div>';    
		$style_include[] ='<meta http-equiv="refresh" content="1; url=admin.php?pilih=admin_menu&aksi=menuadmin" />';    
	}
}

if($_GET['aksi']== 'delmu'){    
	global $koneksi_db;    
	$id     = int_filter($_GET['id']);    
	$hasil = $koneksi_db->sql_query("DELETE FROM `menu_users` WHERE `id`='$id'");    
	if($hasil){    
		$admin.='<div class="sukses">Menu User berhasil dihapus! .</div>';    
		$style_include[] ='<meta http-equiv="refresh" content="1; url=admin.php?pilih=admin_menu&aksi=menuuser" />';    
	}
}

if($_GET['aksi'] == 'menuadmin'){
$tengah = '';
if($_GET['op']== 'up'){

$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM admin");
$data = $koneksi_db->sql_fetchrow ($select);
$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE admin SET ordering='$total' WHERE ordering='".($ID-1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE admin SET ordering=ordering-1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE admin SET ordering='$ID' WHERE ordering='$total'");   
header ("location:admin.php?pilih=admin_menu&aksi=menuadmin");
}

if($_GET['op']== 'down'){
$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM admin");
$data = $koneksi_db->sql_fetchrow ($select);
$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE admin SET ordering='$total' WHERE ordering='".($ID+1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE admin SET ordering=ordering+1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE admin SET ordering='$ID' WHERE ordering='$total'");    
header ("location:admin.php?pilih=admin_menu&aksi=menuadmin");
}
if(isset($_POST['submit'])){
	$menu 		= $_POST['menu'];
	$url 		= $_POST['url'];
	$mod		= $_POST['mod'];
	$ceks 		= mysql_query ("SELECT MAX(ordering) as ordering FROM admin");
    $hasil 		= mysql_fetch_array ($ceks);
    $ordering 	= $hasil['ordering'] + 1;
	$error 	= '';
	if (!$menu)  	$error .= "Error: Silahkan Isi Nama Menunya<br />";
	if (!$url)   	$error .= "Error: Silahkan Isi Urlnya<br />";
	if ($error){
		$tengah .= '<div class="error">'.$error.'</div>';
	}else{
		$hasil  = mysql_query( "INSERT INTO `admin` (`menu` ,`url` ,`mod` ,`ordering`) VALUES ('$menu','$url','$mod','$ordering')" );
		if($hasil){
			$tengah .= '<div class="sukses"><b>Menu Berhasil di Buat.</b></div>';
		}else{
			$tengah .= '<div class="error"><b>Menu Gagal di Buat.</b></div>';
		}
		unset($menu);
		unset($url);
	}

}
$menu     		= !isset($menu) ? '' : $menu;
$url     		= !isset($url) ? '' : $url;

$tengah .= '
<div class="border">
<form method="post" action="">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">Nama Menu</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="menu" value="'.$menu.'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">URL</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="url" value="'.$url.'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">Status Modul</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><select name="mod"><option value="0">Tidak</option><option value="1">Ya</option></select></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">
		<input type="submit" value="Submit" name="submit"></td>
	</tr>
</table>
</form>
</div>';
$tengah .= '
<table cellspacing="0" style="width:100%">
	<tr bgcolor="#c0c0c0">
	<th style="width:25px;text-align:center;padding:10px 5px 10px 5px;border-left:1px solid #ccc;border-top:1px solid #ccc;">No</th>
	<th style="text-align:left;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Nama Menu</th>
	<th style="text-align:center;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Ordering</th>
	<th style="text-align:center;padding:10px 5px 10px 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;">Aksi</th>
	</tr>';

$no =1;
$query 		= mysql_query ("SELECT * FROM `admin` ORDER BY `ordering` ASC");
$cekmax 	= mysql_query ("SELECT MAX(`ordering`) FROM `admin`");
$datacekmax = mysql_fetch_row($cekmax);
$numbers 	= $datacekmax[0];
while($data = mysql_fetch_array($query)) {
$orderd = '<a class="image" href="admin.php?pilih=admin_menu&amp;aksi=menuadmin&amp;op=down&amp;id='.$data['ordering'].'"><img src="images/downarrow.png" border="0" alt="down" /></a>';    
$orderu = '<a class="image" href="admin.php?pilih=admin_menu&amp;aksi=menuadmin&amp;op=up&amp;id='.$data['ordering'].'"><img src="images/uparrow.png" border="0" alt="up" /></a>';    
$ordering_down = $orderd;    
$ordering_up = $orderu;        

if ($data['ordering'] == 1) $ordering_up = '&nbsp;&nbsp;&nbsp;';
if ($data['ordering'] == $numbers) $ordering_down = '&nbsp;';

$warna = empty ($warna) ? 'bgcolor="#efefef"' : '';
$tengah .= '
	<tr '.$warna.'>
	<td style="width:25px;text-align:center;padding:2px;border-left:1px solid #ccc;border-top:1px solid #ccc;">'.$no.'</td>
	<td style="text-align:left;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$data['menu'].'</td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$ordering_up.'  '.$ordering_down.'</td>
	<td style="text-align:center;padding:10px 5px 10px0 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;"><a href="admin.php?pilih=admin_menu&amp;aksi=delma&amp;id='.$data['id'].'">Hapus</a> | <a href="admin.php?pilih=admin_menu&amp;aksi=editma&amp;id='.$data['id'].'">Edit</a></td>
	</tr>';
$no++;		
}	
$tengah .= '<tr><td colspan="4" style="width:25px;text-align:center;padding:5px;border-top:1px solid #ccc;">&nbsp;</td></tr></table>';
$admin .= $tengah;
}

if($_GET['aksi'] == 'editma'){
$id = int_filter ($_GET['id']);
$tengah = '';
if(isset($_POST['submit'])){
	$menu 		= $_POST['menu'];
	$url 		= $_POST['url'];
	$mod		= $_POST['mod'];

	$error 	= '';
	if (!$menu)  	$error .= "Error: Silahkan Isi Nama Menunya<br />";
	if (!$url)   	$error .= "Error: Silahkan Isi Urlnya<br />";
	if ($error){
		$tengah .= '<div class="error">'.$error.'</div>';
	}else{
		$hasil  = mysql_query( "UPDATE `admin` SET `menu`='$menu' ,`url`='$url' ,`mod`='$mod' WHERE `id`='$id'" );
		if($hasil){
			$tengah .= '<div class="sukses"><b>Menu Berhasil di Update.</b></div>';
			$style_include[] ='<meta http-equiv="refresh" content="1; url=admin.php?pilih=admin_menu&aksi=menuadmin" />';	
		}else{
			$tengah .= '<div class="error"><b>Menu Gagal di Update.</b></div>';
		}
	}

}
$query 		= mysql_query ("SELECT * FROM `admin` WHERE `id`='$id'");
$data 		= mysql_fetch_array($query);
$cekmod		= $data['mod'];
$tengah .= '
<div class="border">
<form method="post" action="">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">Nama Menu</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="menu" value="'.$data['menu'].'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">URL</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="url" value="'.$data['url'].'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">Status Modul</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><select name="mod">';
if($cekmod == 1){
	$tengah .= '<option value="0">Tidak</option><option value="1" selected>Ya</option>';
}else{
	$tengah .= '<option value="0" selected>Tidak</option><option value="1">Ya</option>';
}
$tengah .= '
		</select></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">
		<input type="submit" value="Submit" name="submit"></td>
	</tr>
</table>
</form>
</div>';	
$admin .= $tengah;
}

if($_GET['aksi'] == 'editmu'){
$id = int_filter ($_GET['id']);
$tengah = '';
if(isset($_POST['submit'])){
	$menu 		= $_POST['menu'];
	$url 		= $_POST['url'];

	$error 	= '';
	if (!$menu)  	$error .= "Error: Silahkan Isi Nama Menunya<br />";
	if (!$url)   	$error .= "Error: Silahkan Isi Urlnya<br />";
	if ($error){
		$tengah .= '<div class="error">'.$error.'</div>';
	}else{
		$hasil  = mysql_query( "UPDATE `menu_users` SET `menu`='$menu' ,`url`='$url' WHERE `id`='$id'" );
		if($hasil){
			$tengah .= '<div class="sukses"><b>Menu Berhasil di Update.</b></div>';
			$style_include[] ='<meta http-equiv="refresh" content="1; url=admin.php?pilih=admin_menu&aksi=menuuser" />';	
		}else{
			$tengah .= '<div class="error"><b>Menu Gagal di Update.</b></div>';
		}
	}

}
$query 		= mysql_query ("SELECT * FROM `menu_users` WHERE `id`='$id'");
$data 		= mysql_fetch_array($query);

$tengah .= '
<div class="border">
<form method="post" action="">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">Nama Menu</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="menu" value="'.$data['menu'].'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">URL</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="url" value="'.$data['url'].'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">
		<input type="submit" value="Submit" name="submit"></td>
	</tr>
</table>
</form>
</div>';	
$admin .= $tengah;
}

if($_GET['aksi'] == 'menuuser'){
	
$tengah = '';
if($_GET['op']== 'up'){

$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM menu_users");
$data = $koneksi_db->sql_fetchrow ($select);
$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE menu_users SET ordering='$total' WHERE ordering='".($ID-1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE menu_users SET ordering=ordering-1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE menu_users SET ordering='$ID' WHERE ordering='$total'");   
header ("location:admin.php?pilih=admin_menu&aksi=menuuser");
}

if($_GET['op']== 'down'){
$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM menu_users");
$data = $koneksi_db->sql_fetchrow ($select);
$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE menu_users SET ordering='$total' WHERE ordering='".($ID+1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE menu_users SET ordering=ordering+1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE menu_users SET ordering='$ID' WHERE ordering='$total'");    
header ("location:admin.php?pilih=admin_menu&aksi=menuuser");
}

if(isset($_POST['submit'])){
	$menu 		= $_POST['menu'];
	$url 		= $_POST['url'];
	$ceks 		= mysql_query ("SELECT MAX(ordering) as ordering FROM menu_users");
    $hasil 		= mysql_fetch_array ($ceks);
    $ordering 	= $hasil['ordering'] + 1;
	$error 	= '';
	if (!$menu)  	$error .= "Error: Silahkan Isi Nama Menunya<br />";
	if (!$url)   	$error .= "Error: Silahkan Isi Urlnya<br />";
	if ($error){
		$tengah .= '<div class="error">'.$error.'</div>';
	}else{
		$hasil  = mysql_query( "INSERT INTO `menu_users` (`menu` ,`url` ,`ordering`) VALUES ('$menu','$url','$ordering')" );
		if($hasil){
			$tengah .= '<div class="sukses"><b>Menu Berhasil di Buat.</b></div>';
		}else{
			$tengah .= '<div class="error"><b>Menu Gagal di Buat.</b></div>';
		}
		unset($menu);
		unset($url);
	}

}
$menu     		= !isset($menu) ? '' : $menu;
$url     		= !isset($url) ? '' : $url;

$tengah .= '
<div class="border">
<form method="post" action="">
<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">Nama Menu</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="menu" value="'.$menu.'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">URL</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">:</td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"><input type="text" name="url" value="'.$url.'" size="25"></td>
	</tr>
	<tr>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0"></td>
		<td style="padding-left: 5px; padding-right: 5px; padding-top: 5px; padding-bottom: 0">
		<input type="submit" value="Submit" name="submit"></td>
	</tr>
</table>
</form>
</div>';
$tengah .= '
<table cellspacing="0" style="width:100%">
	<tr bgcolor="#c0c0c0">
	<th style="width:25px;text-align:center;padding:10px 5px 10px 5px;border-left:1px solid #ccc;border-top:1px solid #ccc;">No</th>
	<th style="text-align:left;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Nama Menu</th>
	<th style="text-align:center;padding:10px 5px 10px 5px;border-top:1px solid #ccc;border-left:1px solid #ccc;">Ordering</th>
	<th style="text-align:center;padding:10px 5px 10px 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;">Aksi</th>
	</tr>';

$no =1;
$query 		= mysql_query ("SELECT * FROM `menu_users` ORDER BY `ordering` ASC");
$cekmax 	= mysql_query ("SELECT MAX(`ordering`) FROM `menu_users`");
$datacekmax = mysql_fetch_row($cekmax);
$numbers 	= $datacekmax[0];
while($data = mysql_fetch_array($query)) {
$orderd = '<a class="image" href="admin.php?pilih=admin_menu&amp;aksi=menuuser&amp;op=down&amp;id='.$data['ordering'].'"><img src="images/downarrow.png" border="0" alt="down" /></a>';    
$orderu = '<a class="image" href="admin.php?pilih=admin_menu&amp;aksi=menuuser&amp;op=up&amp;id='.$data['ordering'].'"><img src="images/uparrow.png" border="0" alt="up" /></a>';    
$ordering_down = $orderd;    
$ordering_up = $orderu;        

if ($data['ordering'] == 1) $ordering_up = '&nbsp;&nbsp;&nbsp;';
if ($data['ordering'] == $numbers) $ordering_down = '&nbsp;';

$warna = empty ($warna) ? 'bgcolor="#efefef"' : '';
$tengah .= '
	<tr '.$warna.'>
	<td style="width:25px;text-align:center;padding:2px;border-left:1px solid #ccc;border-top:1px solid #ccc;">'.$no.'</td>
	<td style="text-align:left;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$data['menu'].'</td>
	<td style="text-align:center;padding:2px;border-top:1px solid #ccc;border-left:1px solid #ccc;">'.$ordering_up.'  '.$ordering_down.'</td>
	<td style="text-align:center;padding:10px 5px 10px0 5px;border-right:1px solid #ccc;border-top:1px solid #ccc;border-left:1px solid #ccc;"><a href="admin.php?pilih=admin_menu&amp;aksi=delmu&amp;id='.$data['id'].'">Hapus</a> | <a href="admin.php?pilih=admin_menu&amp;aksi=editmu&amp;id='.$data['id'].'">Edit</a></td>
	</tr>';
$no++;		
}	
$tengah .= '<tr><td colspan="4" style="width:25px;text-align:center;padding:5px;border-top:1px solid #ccc;">&nbsp;</td></tr></table>';
$admin .= $tengah;
}

echo $admin;

?>