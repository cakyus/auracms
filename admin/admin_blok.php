<?

if (!defined('AURACMS_admin')) {
    Header("Location: ../index.php");
    exit;
}

if (!cek_login ()){
  header ("location: index.php");
  exit;
}else{


$admin ='<h4 class="bg">Blok Manager </h4>';
$admin .='<div class="border"><a href="?pilih=admin_blok"><b>Home</b></a> | <a href="'.$adminfile.'.php?pilih=admin_blok&amp;aksi=add"><b>Buat Blok Baru</b></a></div>';

$admin .= <<<Js

 <script type="text/javascript" language="javascript">
    //<![CDATA[
    // Updates the title of the frameset if possible (ns4 does not allow this)
    if (typeof(parent.document) != 'undefined' && typeof(parent.document) != 'unknown'
        && typeof(parent.document.title) == 'string') {
        parent.document.title = 'Administrasi';
    }
    
    // js form validation stuff
    var confirmMsg  = 'Apakah anda ingin ';
   //]]>
    </script>
    <script type="text/javascript" language="javascript">
    //<![CDATA[
    function confirmLink(theLink, theSqlQuery)
{
    // Confirmation is not required in the configuration file
    // or browser is Opera (crappy js implementation)
    if (confirmMsg == '' || typeof(window.opera) != 'undefined') {
        return true;
    }

    var is_confirmed = confirm(confirmMsg + ' :' + theSqlQuery);
    if (is_confirmed) {
        theLink.href += '&is_js_confirmed=1';
    }

    return is_confirmed;
}
//]]>
</script>


<script language="javascript" src="js/fat.js" type="text/javascript"></script>
<script language="javascript" type="text/javascript">
 //<![CDATA[
var xmlhttp = false;
try {
xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
} catch (e) {
try {
xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
} catch (E) {
xmlhttp = false;
}
}
if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
xmlhttp = new XMLHttpRequest();
}


function delrow(tbid, trid) {
	var tb= document.getElementById(tbid);
	var tr= document.getElementById(trid);
	tb.removeChild(tr);
  }

function hapus(serverPage,tbody,iddata){
	
	var hapus_ga = confirm ('Apakah Anda Yakin Ingin Menghapus Data Ini ??');
	
	if (hapus_ga == true){
	xmlhttp.open("GET", serverPage);
	xmlhttp.onreadystatechange = function() {
		if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
		//obj.innerHTML = xmlhttp.responseText;
		var responservertext = xmlhttp.responseText;
		if (responservertext != ""){
		document.getElementById('responseajax').innerHTML = '<div class="border"><table width="100%" class="bodyline"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><div class="error">' + responservertext + '</div></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></div>';
		}
		
		
	Fat.fade_element(iddata,null,1000,'#FF3333');
	
	if (responservertext == ""){
	 setTimeout("delrow('"+tbody+"','"+iddata+"')", 1000);
	}else {
	Fat.fade_element(iddata,null,1000,'#efefef','#ff3333');
	}
	
	
		}
		}
		xmlhttp.send(null);
		
		
		}
	
}

 //]]>
</script> 

Js;
if($_GET['aksi']=="up"){

$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM blok");
$data = $koneksi_db->sql_fetchrow ($select);
$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE blok SET ordering='$total' WHERE ordering='".($ID-1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE blok SET ordering=ordering-1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE blok SET ordering='$ID' WHERE ordering='$total'");   
header ("location:".$adminfile.".php?pilih=admin_blok");
}

if($_GET['aksi']=="down"){
$ID = int_filter ($_GET['id']);
$select = $koneksi_db->sql_query ("SELECT MAX(ordering) as sc FROM blok");
$data = $koneksi_db->sql_fetchrow ($select);
$total = $data['sc'] + 1;
$update = $koneksi_db->sql_query ("UPDATE blok SET ordering='$total' WHERE ordering='".($ID+1)."'"); 
$update = $koneksi_db->sql_query ("UPDATE blok SET ordering=ordering+1 WHERE ordering='$ID'");
$update = $koneksi_db->sql_query ("UPDATE blok SET ordering='$ID' WHERE ordering='$total'");    
header ("location:".$adminfile.".php?pilih=admin_blok");
}



if($_GET['aksi']==""){
global $koneksi_db;

$hasil = $koneksi_db->sql_query(  "SELECT * FROM blok ORDER BY ordering" );
$cekmax = mysql_query ("SELECT MAX(`ordering`) FROM `blok`");
$datacekmax = mysql_fetch_row($cekmax);
$numbers = $datacekmax[0];

$admin .='
<table border="0" style="width:100%" cellpadding="0" bgcolor="#d5d5d5" cellspacing="1">
  <tbody id="rowbody">
  <tr>
    <td><b>Nama Blok</b></td>
    <td align="center"><b>Status</b></td>
    <td align="center"><b>Order</b></td>
    <td align="center"><b>Posisi</b></td>
    <td align="center" colspan="2"><b>Aksi</b></td>
  </tr>';
while ($data = $koneksi_db->sql_fetchrow($hasil)) {
$id = $data['id'];
$published = ($data['published'] == 1) ? '<a href="'.$adminfile.'.php?pilih=admin_blok&amp;aksi=pub&amp;pub=tidak&amp;id='.$data['id'].'"><img src="images/ya.png" border="0" alt="ya" /></a>' : '<a href="?pilih=admin_blok&amp;aksi=pub&amp;pub=ya&amp;id='.$data['id'].'"><img src="images/tidak.png" border="0" alt="no" /></a>';
$posisinya = ($data['posisi'] == 1) ? '<a href="'.$adminfile.'.php?pilih=admin_blok&amp;aksi=pindah&amp;id='.$data[0].'&amp;posisi=0">Pindah Kiri</a>' : '<a href="?pilih=admin_blok&amp;aksi=pindah&amp;id='.$data[0].'&amp;posisi=1">Pindah Kanan</a>';
$orderd = '<a class="image" href="'.$adminfile.'.php?pilih=admin_blok&amp;aksi=down&amp;id='.$data['ordering'].'"><img src="images/downarrow.png" border="0" alt="down" /></a>';    
$orderu = '<a class="image" href="'.$adminfile.'.php?pilih=admin_blok&amp;aksi=up&amp;id='.$data['ordering'].'"><img src="images/uparrow.png" border="0" alt="up" /></a>';    
$ordering_down = $orderd;    
$ordering_up = $orderu;        

if ($data['ordering'] == 1) $ordering_up = '&nbsp;&nbsp;&nbsp;';
if ($data['ordering'] == $numbers) $ordering_down = '&nbsp;';

$admin.='
  <tr  id="id_'.$id.'">
    <td bgcolor="#FFFFFF">'.$data[1].'</td>
    <td bgcolor="#FFFFFF" align="center">'.$published.'</td>
    <td bgcolor="#FFFFFF" align="center">'.$ordering_up.'  '.$ordering_down.'</td>
    <td bgcolor="#FFFFFF" align="center">'.$posisinya.'</td>
    <td bgcolor="#FFFFFF" align="center"><a href="'.$adminfile.'.php?pilih=admin_blok&amp;aksi=edit&amp;id='.$data[0].'"><img border="0" src="images/edit.gif" width="24" height="15" alt="edit" /></a></td>
    <td bgcolor="#FFFFFF" align="center"><a class="image" href="javascript:hapus(\'mod/ajax/blok_deletes.php?id='.$id.'\',\'rowbody\',\'id_'.$id.'\');"><img src="images/delete_button.gif" border="0" alt="delete Blok" /></a></td>
  </tr>';
    }
$admin .='</tbody></table>';
$admin .= '<div id="responseajax"></div>';
}



if($_GET['aksi']=="add"){
global $koneksi_db,$theme,$error;

if (isset($_POST['submit'])) {

    $judul     = $_POST['judul'];
    $isi         = $_POST['isi'];
    $posisi    = $_POST['posisi'];
    $ceks = $koneksi_db->sql_query ("SELECT MAX(ordering) as ordering FROM blok");
    $hasil = $koneksi_db->sql_fetchrow ($ceks);
    $ordering = $hasil['ordering'] + 1;
    $error = '';

    if (!$judul)  $error .= "Error: Judul Blok Harus diisi, silahkan ulangi.<br />";
    if (!$isi)      $error .= "Error: Isi Blok tidak Boleh Kosong, silahkan ulangi.<br />";

    if ($error != ''){
        $admin.='<div class="error">'.$error.'</div>';
    }else{

    $hasil = $koneksi_db->sql_query( "INSERT INTO blok (namablok,isi,posisi,ordering,published) VALUES ('$judul','$isi','$posisi','$ordering','1')" );
     if($hasil){
        $admin.='<div class="sukses">Blok berhasil dibuat..</div>';
        $style_include[] = '<meta http-equiv="refresh" content="3; url='.$adminfile.'.php?pilih=admin_blok" />';
    }
    }

}

$judul = !isset($judul) ? null : $judul;
$isi = !isset($isi) ? null : $isi;
$admin .='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="3" class="bodyline"><tr><td class="bgcolor1">';
$admin.='<b>Buat Blok Baru</b><br />
<blockquote><b>Aturan :</b><br />
Harus menggunakan format HTML.<br />
Catatan : aturan ini sama dengan aturan membuat halaman web.</blockquote>

<form method="post" action="#">
    <table>
        <tr>
            <td>Judul Blok</td>
            <td valign="top">:</td>
            <td><input type="text" size="30" name="judul" value="'.$judul.'" />
    </td>
        </tr>
        <tr>
            <td valign="top">Isi Blok</td>
            <td valign="top">:</td>
            <td><textarea name="isi" rows="10" cols="35">'.$isi.'</textarea></td>
        </tr>
        <tr>
            <td valign="top">Posisi Blok</td>
            <td valign="top">:</td>
            <td><select name="posisi"><option value="0">KIRI</option><option value="1">KANAN</option></select></td>
        </tr>
        <tr>
            <td></td><td></td><td><input type="submit" name="submit" value="Submit" /></td>
        </tr>
    </table>
</form>';
$admin .='</td></tr></table></td></tr></table>';

}

if($_GET['aksi']=="pindah"){
global $koneksi_db;
    $id = int_filter ($_GET['id']);
    $posisi = $_GET['posisi'];

    $hasil = $koneksi_db->sql_query( "UPDATE blok SET posisi='$posisi' WHERE id='$id'" );

    if($hasil){
    $ke = ($posisi==0)?"KIRI":"KANAN";
    $admin.='<div class="sukses">Posisi Blok telah pindah ke : '.$ke.'</div>';
    $style_include[] ='<meta http-equiv="refresh" content="3; url='.$adminfile.'.php?pilih=admin_blok" />';
    }

}


if($_GET['aksi']=="edit"){
global $koneksi_db,$error,$kanan,$kiri;

$id = int_filter ($_GET['id']);

if (isset($_POST['submit'])) {

$judul     = $_POST['judul'];
$isi         = $_POST['isi'];
$posisi    = $_POST['posisi'];

if (!$judul or !$isi)  $error .= "Error: Formulir belum terisi dengan benar, silahkan ulangi.<br />";

if ($error){
$admin.='<div class="error">'.$error.'</div>';
}else{

$hasil = $koneksi_db->sql_query( "UPDATE blok SET namablok='$judul', isi='$isi', posisi='$posisi' WHERE id='$id'" );
if($hasil){
$admin.='<div class="sukses">Blok telah di updated</div>';
$style_include[] ='<meta http-equiv="refresh" content="3; url='.$adminfile.'.php?pilih=admin_blok" />';
}

}

}

$hasil = $koneksi_db->sql_query("SELECT * FROM blok WHERE id=$id" );
while ($data = $koneksi_db->sql_fetchrow($hasil)) {
    $id=$data[0];
    $judul=$data[1];
    $isi=$data[2];
    $posisi=$data[3];
    }

$admin .='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" border="0" cellspacing="0" cellpadding="3" class="bodyline"><tr><td class="bgcolor1">';
$admin.='<b>Edit Blok</b><br />
<form method="post" action="" >
    <table>
        <tr>
            <td>Judul Blok</td>
            <td valign="top">:</td>
            <td><input type="hidden" name="id" value="'.$id.'" />
            <input type="text" size="30" name="judul" value="'.$judul.'" /></td>
        </tr>
        <tr>
            <td valign="top">Isi Blok</td>
            <td valign="top">:</td>
            <td><textarea name="isi" rows="10" cols="35">'.htmlentities($isi).'</textarea></td>
        </tr>
        <tr>
            <td valign="top">Posisi Blok</td>
            <td valign="top">:</td>';


if ($posisi==0){
$kiri="selected=\"selected\"";
} else {
$kanan="selected=\"selected\"";
}
$admin.='
            <td><select name="posisi"><option value="0" '.$kiri.'>KIRI</option><option value="1" '.$kanan.'>KANAN</option></select></td>
        </tr>
        <tr>
            <td></td><td></td><td><input type="submit" name="submit" value="submit" /></td>
        </tr>
    </table>
</form>';
$admin .='</td></tr></table></td></tr></table>';

}


if($_GET['aksi']=="hapus"){
    global $koneksi_db;
    $id = int_filter ($_GET['id']);
    $hasil = $koneksi_db->sql_query("DELETE FROM blok WHERE id='$id'");
    if($hasil){
    $admin.='<table width="100%" border="0" cellspacing="0" cellpadding="0" class="middle"><tr><td><table width="100%" class="bodyline"><tr><td align="left"><img src="images/info.gif" border="0"></td><td align="center"><font class="option"><b><br />Blok  telah di delete!<br /></font></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></td></tr></table>';
    $admin.='<meta http-equiv="refresh" content="3; url='.$adminfile.'.php?pilih=admin_blok">';
    }
}

if ($_GET['aksi'] == 'pub'){

    if ($_GET['pub'] == 'tidak'){
    $id = int_filter ($_GET['id']);
    $koneksi_db->sql_query ("UPDATE blok SET published=0 WHERE id='$id'");
        }

    if ($_GET['pub'] == 'ya'){
    $id = int_filter ($_GET['id']);
    $koneksi_db->sql_query ("UPDATE blok SET published=1 WHERE id='$id'");
        }

    header ("location:".$adminfile.".php?pilih=admin_blok");
}

echo $admin;


}
?>