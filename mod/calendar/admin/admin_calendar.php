<?
if (!defined('AURACMS_admin')) {
    Header("Location: ../index.php");
    exit;
}



if (!cek_login ()){
    header ("location:index.php");
    exit;
}

$content = '';

$content .= '<h4 class="bg">Kalender Event</h4>';
$content .= '
<script type="text/javascript" language="javascript">
/*<![CDATA[*/
   function GP_popupConfirmMsg(msg) { //v1.0
  document.MM_returnValue = confirm(msg);
}
function flevPopupLink(){// v1.2
var v1=arguments,v2=window.open(v1[0],v1[1],v1[2]), v3=(v1.length>3)?v1[3]:false;if (v3){v2.focus();}document.MM_returnValue=false;
}
/*]]>*/
</script>';

$content .= '<div class="border">';
$content .= '<p><a href="admin.php?pilih=calendar&amp;mod=yes&amp;action=add">Tambah Data</a> | <a href="admin.php?pilih=calendar&amp;mod=yes">Lihat Daftar Kalender</a></p>';
$content .= '</div>';

$style_include[] = '
<style type="text/css">
/*<![CDATA[*/
@import url("themes/auracms/css/form.css");
/*]]>*/
</style>';


if (!isset ($_GET['action'])) $_GET['action'] = NULL;

switch ($_GET['action']){
	
	
case 'add':

$style_include[] = '<link rel="stylesheet" media="screen" href="mod/calendar/css/dynCalendar.css" />';
$content .= '
<script language="javascript" type="text/javascript" src="mod/calendar/js/browserSniffer.js"></script>
<script language="javascript" type="text/javascript" src="mod/calendar/js/dynCalendar.js"></script>';
$wktmulai = <<<eof
<script language="JavaScript" type="text/javascript">
    
    /**
    * Example callback function
    */
    /*<![CDATA[*/
    function exampleCallback_ISO2(date, month, year)
    {
        if (String(month).length == 1) {
            month = '0' + month;
        }
    
        if (String(date).length == 1) {
            date = '0' + date;
        }    
        document.forms['input_siswa'].waktu_mulai.value = year + '-' + month + '-' + date;
        document.forms['input_siswa'].waktu_akhir.value = year + '-' + month + '-' + date;
        document.forms['input_siswa'].waktu_akhir.focus();
    }
    calendar2 = new dynCalendar('calendar2', 'exampleCallback_ISO2');
    calendar2.setMonthCombo(true);
    calendar2.setYearCombo(true);
    
/*]]>*/   
</script>
eof;

$wktakhir = <<<eof
<script language="JavaScript" type="text/javascript">
    
    /**
    * Example callback function
    */
    /*<![CDATA[*/
    function exampleCallback_ISO3(date, month, year)
    {
        if (String(month).length == 1) {
            month = '0' + month;
        }
    
        if (String(date).length == 1) {
            date = '0' + date;
        }    
        document.forms['input_siswa'].waktu_akhir.value = year + '-' + month + '-' + date;
    }
    calendar3 = new dynCalendar('calendar3', 'exampleCallback_ISO3');
    calendar3.setMonthCombo(true);
    calendar3.setYearCombo(true);
/*]]>*/     
</script>
eof;

$content .= <<<cek
<script language="javascript" type="text/javascript">
/*<![CDATA[*/
function cek(){

if (document.input_siswa.judul.value=="") {
alert("Judul agendanya apa?");
document.input_siswa.judul.focus();
return false
}

if (document.input_siswa.waktu_mulai.value=="") {
alert("Waktu Mulai Kapan?!");
document.input_siswa.waktu_mulai.focus();
return false
}
if (document.input_siswa.waktu_akhir.value=="") {
alert("Waktu akhir kapan?!");
document.input_siswa.waktu_akhir.focus();
return false
}

if (document.input_siswa.isi.value=="") {
alert("Isi Kalendernya ?");
document.input_siswa.isi.focus();
return false
}

return true
}
/*]]>*/  
</script>
cek;


if (isset ($_POST['submit'])){
	
$judul = cleantext($_POST['judul']);	
$waktu_mulai = cleantext($_POST['waktu_mulai']);	
$waktu_akhir = cleantext($_POST['waktu_akhir']);	
$isi = cleantext($_POST['isi']);	
$background = cleantext($_POST['background']);	
$color = cleantext($_POST['color']);

$error = '';

foreach ($_POST as $K=>$V){
	if (empty($V)){
		$error = ' ';
	}
}	
	
	
if ($error != ''){
        $content .='<div class="border"><table width="100%" class="bodyline"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><div class="error">Error Pengisian Data, Silahkan Lengkapi Form Tersebut</div></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></div>';
	
}else {
	$masuk = mysql_query ("INSERT INTO `tbl_kalender` (`judul`,`waktu_mulai`,`waktu_akhir`,`isi`,`background`,`color`) VALUES ('$judul','$waktu_mulai','$waktu_akhir','$isi','$background','$color')");
if ($masuk) {
    $content .='<div class="sukses">Data Berhasil Dimasukkan</div>'; 
unset($_POST);
}else {
$content .='<div class="error">Data Gagal dimasukkan</div>';
}//end else masuk
}//end else error
	
}

$content .= <<<scr
<script type="text/javascript">
/*<![CDATA[*/
ubahbackground=function(obj,ini){
var Obj = document.getElementById(obj);
try{
Obj.style.background = ini.value;
}catch(e){
alert('Tabel Warna Invalid');
}

};

ubahcolor=function(obj,ini){
var Obj = document.getElementById(obj);
try{
Obj.style.color = ini.value;
}catch(e){
alert('Tabel Warna Invalid');
}
	
};
/*]]>*/
</script>

scr;


$content .='<div class="border">';
$content .= '<form method="post" action="#" name="input_siswa" id="input_siswa" onsubmit="return cek()"><table width="100%" align="center">
	<tr>
		<td width="20%">Judul</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('judul','').'</td>
	</tr>
	<tr>
		<td width="20%">Waktu Mulai</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('waktu_mulai','','text',15).' ' . $wktmulai .'</td>
	</tr>
	<tr>
		<td width="20%">Waktu Akhir</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('waktu_akhir','','text',15).' ' .$wktakhir.'</td>
	</tr>
	<tr>
		<td width="20%">Background Color</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('background','#d1d1d1','text',15,' onblur="return ubahbackground(\'ubahwarna\',this);"').' <span id="ubahwarna" style="background:#efefef">&nbsp;&nbsp;<span id="tulisanwarna">16</span>&nbsp;&nbsp;</span></td>
	</tr>
	<tr>
		<td width="20%">Color</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('color','#999999','text',15,' onblur="return ubahcolor(\'tulisanwarna\',this);"').'</td>
	</tr>
	<tr>
		<td width="20%" valign="top">Isi</td>
		<td width="1%" valign="top">:</td>
		<td width="49%">'.input_textarea ('isi','',$rows=5,$cols=40,$opt='').'</td>
	</tr>
	<tr>
		<td width="20%">&nbsp;</td>
		<td width="1%">&nbsp;</td>
		<td width="49%"><input type="submit" name="submit" value="Simpan" class="submit" /></td>
	</tr>';
	
	
$content .= '</table></form>';
$content .='</div>';

break;

case 'edit':
$style_include[] = '<link rel="stylesheet" media="screen" href="css/dynCalendar.css" />';
$content .= '
<script language="javascript" type="text/javascript" src="mod/calendar/js/browserSniffer.js"></script>
<script language="javascript" type="text/javascript" src="mod/calendar/js/dynCalendar.js"></script>';
$wktmulai = <<<eof
<script language="JavaScript" type="text/javascript">
    
    /**
    * Example callback function
    */
    /*<![CDATA[*/
    function exampleCallback_ISO2(date, month, year)
    {
        if (String(month).length == 1) {
            month = '0' + month;
        }
    
        if (String(date).length == 1) {
            date = '0' + date;
        }    
        document.forms['input_siswa'].waktu_mulai.value = year + '-' + month + '-' + date;
        document.forms['input_siswa'].waktu_akhir.value = year + '-' + month + '-' + date;
        document.forms['input_siswa'].waktu_akhir.focus();
    }
    calendar2 = new dynCalendar('calendar2', 'exampleCallback_ISO2');
    calendar2.setMonthCombo(true);
    calendar2.setYearCombo(true);
/*]]>*/   
</script>
eof;

$wktakhir = <<<eof
<script language="javascript" type="text/javascript">
    
    /**
    * Example callback function
    */
    /*<![CDATA[*/
    function exampleCallback_ISO3(date, month, year)
    {
        if (String(month).length == 1) {
            month = '0' + month;
        }
    
        if (String(date).length == 1) {
            date = '0' + date;
        }    
        document.forms['input_siswa'].waktu_akhir.value = year + '-' + month + '-' + date;
    }
    calendar3 = new dynCalendar('calendar3', 'exampleCallback_ISO3');
    calendar3.setMonthCombo(true);
    calendar3.setYearCombo(true);
 /*]]>*/  
</script>
eof;

$content .= <<<cek
<script language="javascript" type="text/javascript">
/*<![CDATA[*/
function cek(){

if (document.input_siswa.judul.value=="") {
alert("Judul agendanya apa?");
document.input_siswa.judul.focus();
return false
}

if (document.input_siswa.waktu_mulai.value=="") {
alert("Waktu Mulai Kapan?!");
document.input_siswa.waktu_mulai.focus();
return false
}
if (document.input_siswa.waktu_akhir.value=="") {
alert("Waktu akhir kapan?!");
document.input_siswa.waktu_akhir.focus();
return false
}

if (document.input_siswa.isi.value=="") {
alert("Isi Kalendernya ?");
document.input_siswa.isi.focus();
return false
}

return true
}
/*]]>*/
</script>
cek;

$content .= <<<scr
<script type="text/javascript">
/*<![CDATA[*/
ubahbackground=function(obj,ini){
var Obj = document.getElementById(obj);
try{
Obj.style.background = ini.value;
}catch(e){
alert('Tabel Warna Invalid');
}

};

ubahcolor=function(obj,ini){
var Obj = document.getElementById(obj);
try{
Obj.style.color = ini.value;
}catch(e){
alert('Tabel Warna Invalid');
}
	
};
/*]]>*/
</script>

scr;




$id = int_filter($_GET['id']);

if (isset ($_POST['submit'])){
	
$judul = cleantext($_POST['judul']);	
$waktu_mulai = cleantext($_POST['waktu_mulai']);	
$waktu_akhir = cleantext($_POST['waktu_akhir']);	
$isi = cleantext($_POST['isi']);	
$background = cleantext($_POST['background']);	
$color = cleantext($_POST['color']);		


$error = '';

foreach ($_POST as $K=>$V){
	if (empty($V)){
		$error = ' ';
	}
}	
	
	
if ($error != ''){
        $content .='<div class="border"><table width="100%" class="bodyline"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><div class="error">Error Pengisian Data, Silahkan Lengkapi Form Tersebut</div></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></div>';	
}else {
	$masuk = mysql_query ("UPDATE `tbl_kalender` SET `judul`='$judul',`waktu_mulai`='$waktu_mulai',`waktu_akhir`='$waktu_akhir',`isi`='$isi',`background`='$background',`color`='$color' WHERE `id`='$id'");
if ($masuk) {
    $content .='<div class="sukses">Data Berhasil Diupdate</div>'; 
unset($_POST);
}else {
$content .='<div class="error">Data Gagal diupdate</div>';
}
}



	
}

$qs = mysql_query ("SELECT * FROM `tbl_kalender` WHERE `id`='$id'");
$get = mysql_fetch_assoc($qs);


$content .= '<div class="border">';
$content .= '<form method="post" action="#" name="input_siswa" id="input_siswa" onsubmit="return cek()"><table style="width:100%" align="center">
	<tr>
		<td width="20%">Judul</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('judul',$get['judul']).'</td>
	</tr>
	<tr>
		<td width="20%">Waktu Mulai</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('waktu_mulai',$get['waktu_mulai'],'text',15).' ' . $wktmulai .'</td>
	</tr>
	<tr>
		<td width="20%">Waktu Akhir</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('waktu_akhir',$get['waktu_akhir'],'text',15).' ' .$wktakhir.'</td>
	</tr>
	<tr>
		<td width="20%">Background Color</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('background',$get['background'],'text',15,'onblur="return ubahbackground(\'ubahwarna\',this);"').' <span id="ubahwarna" style="background:'.$get['background'].'">&nbsp;&nbsp;<span id="tulisanwarna" style="color:'.$get['color'].'">16</span>&nbsp;&nbsp;</span></td>
	</tr>
	<tr>
		<td width="20%">Color</td>
		<td width="1%">:</td>
		<td width="49%">'.input_text ('color',$get['color'],'text',15,'onblur="return ubahcolor(\'tulisanwarna\',this);"').'</td>
	</tr>
	<tr>
		<td width="20%">Isi</td>
		<td width="1%">:</td>
		<td width="49%">'.input_textarea ('isi',$get['isi'],$rows=5,$cols=40,$opt='').'</td>
	</tr>
	<tr>
		<td width="20%">&nbsp;</td>
		<td width="1%">&nbsp;</td>
		<td width="49%"><input type="submit" name="submit" value="Edit" class="submit" /></td>
	</tr>';
	
	
$content .= '</table></form>';
$content .= '</div>';
break;


case 'delete':
$id = int_filter($_GET['id']);
$del = mysql_query ("DELETE FROM `tbl_kalender` WHERE `id`='$id'");
if ($del) {
    $content .='<div class="border"><table width="100%" class="bodyline"><tr><td align="left"><img src="images/info.gif" border="0"></td><td align="center"><div class="sukses">Data Berhasil Dihapus</div></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></div>'; 
$content .= '<div class="border"><center><a href="admin.php?pilih=calendar&amp;mod=yes">Back</a></center></div>';
}else {
        $content .='<div class="border"><table width="100%" class="bodyline"><tr><td align="left"><img src="images/warning.gif" border="0"></td><td align="center"><div class="error">Data gagal di Hapus</div></td><td align="right"><img src="images/warning.gif" border="0"></td></tr></table></div>';
$content .= '<div class="border"><center><a href="admin.php?pilih=calendar&amp;mod=yes">Back</a></center></div>';
}
break;
	
default:


$limit = 10;
if (empty($_GET['offset']) and !isset ($_GET['offset'])) {
$offset = 0;
}else {
$offset = int_filter ($_GET['offset']);	
}


$query_add = '';
if (!empty ($_GET['waktu'])){
$query_add = "WHERE `waktu_mulai` LIKE '%".cleantext($_GET['waktu'])."%'";	
} 




$num = mysql_query("SELECT COUNT(id) as t FROM `tbl_kalender` $query_add");
$rows = mysql_fetch_row ($num);
$jumlah = $rows[0];
mysql_free_result ($num);
$a = new paging ($limit);

// Pembagian halaman dimulai
 if (!isset ($_GET['pg'],$_GET['stg'])){
	  $_GET['pg'] = 1;
	  $_GET['stg'] = 1;
  }
  
$waktu_value = !isset ($_GET['waktu']) ? date('Y-m-d') : $_GET['waktu']; 
$content .= '<div class="border">';
$content .= '<form method="get" action="#">Waktu Mulai : <input type="text" name="waktu" value="'.$waktu_value.'" /> <input type="submit" name="submit_kal" value="cari" /><input type="hidden" name="pilih" value="calendar" /><input type="hidden" name="mod" value="yes" /><br />Format : YYYY-mm-dd / YYYY-mm / YYYY / mm-dd</form>';  
$content .= '</div><br />';
  


$halamanpage = $a-> getPaging($jumlah, $_GET['pg'], $_GET['stg']);	
if (!empty($halamanpage)){
$content .= '<div class="border">'; 
$content.= $halamanpage;
$content .= '</div>'; 
}

$query = mysql_query ("SELECT * FROM `tbl_kalender` $query_add ORDER BY `waktu_mulai` DESC LIMIT $offset, $limit");
while ($getdata = mysql_fetch_assoc($query)){
$content .= '<table style="border:1px solid #e1e1e1;" width="100%">
	<tr>
		<td width="20%"><b>'.$getdata['judul'].'</b> <a href="admin.php?pilih=calendar&amp;mod=yes&amp;action=delete&amp;id='.$getdata['id'].'" onclick="GP_popupConfirmMsg(\'Apakah Anda Yakin Ingin Menghapus Data Ini\')"><img src="images/delete.gif" align="middle" border="0" alt="del" /></a> <a href="admin.php?pilih=calendar&amp;mod=yes&amp;action=edit&amp;id='.$getdata['id'].'"><img src="images/edit.gif" align="middle" border="0" alt="edit" /></a></td>
	</tr>
	<tr>
		<td width="20%"><small>'.nl2br($getdata['isi']).'</small></td>
	</tr>
	<tr>
		<td width="20%"><hr size="1" style="border:1px solid #e1e1e1" />Waktu Mulai : <i>'.$getdata['waktu_mulai'].'</i></td>
	</tr>
	<tr>
		<td width="20%">Waktu Akhir : <i>'.$getdata['waktu_akhir'].'</i></td>
	</tr>
	<tr>
		<td width="20%">&nbsp;</td>
	</tr>';
	
	
$content .= '</table>';	
}

break;	
	
	
}


echo $content;
?>
