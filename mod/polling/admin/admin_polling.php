<?php

if (!defined('AURACMS_admin')) {
    Header("Location: ../index.php");
    exit;
}

$admin = '';
if (!cek_login ()){
$admin .= 'Access Denied!.... You Must Login First';

}


$index_hal = 1;

$admin .= '<h4 class="bg">Polling Manager</h4>';
$admin .= "<div class=\"border\"><a href='admin.php?pilih=polling&amp;mod=yes'>Home</a> | <a href='admin.php?pilih=polling&amp;mod=yes&amp;aksi=add'>Tambah Jajak pendapat</a></div>";



$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : null;
extract($_REQUEST);


if ($aksi == 'type'){
	$types = $_GET['types'];
$hasil11 = mysql_query("UPDATE polling SET type='$types' WHERE pid='$pid'");	
header ("location:admin.php?pilih=polling&amp;mod=yes");
		
	
}





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
    <script  type="text/javascript" language="javascript">
    
    function confirmLink(theLink, theSqlQuery)
{
	//<![CDATA[
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

##########################
## publikaso
##########################

if ($_GET['aksi'] =='public'){
$hasil = mysql_query("SELECT * FROM polling");

	while($data = mysql_fetch_array($hasil)){ 

 $hasil1 = mysql_query("UPDATE polling SET public='tidak'");		
	

}
mysql_free_result ($hasil);	
 $hasil1 = mysql_query("UPDATE polling SET public='ya' WHERE pid='$pid'");	
 header ("location:admin.php?pilih=polling&mod=yes");
}

##################################################
## Delete Polling
##################################################

if ($_GET['aksi'] =='delete'){
$pid = int_filter($_GET['id']);	
$query = mysql_query("DELETE FROM `polling` WHERE `pid`='$pid'");	
if ($query) echo '';
else {
	echo 'Data Gagal Didelete';
	echo '<br>'.mysql_error();
}
	
}

#################################################
## Add Polling
#################################################
if(!isset($aksi)){	
$admin.=  "



<div align='left'>Silakan pilih Polling yang akan diPublikasi:";
            $hasil = mysql_query("SELECT * FROM polling");
            $admin.= "<table  border='0' width='100%' style=\"\">
            <tbody id=\"rowbody\">
             <tr style=\"font-weight:bold;height:20px;padding:7px;background:#d1d1d1\">
    <td width='4%'>No</td>
    <td width='47%'>Questions</td>
    <td width='25%' align='center'>Created</td>
    <td width='5%' align='center'>Public</td>
    <td width='14%' align='center'>Actions</td>
   
  </tr>";
            
            $no = 1 ;
			while($data = mysql_fetch_array($hasil))
			{   
				$td =$no % 2 ;
                $class ="table".$td;
				$PID = $data["pid"];
				$PJUDUL = $data["pjudul"];
				$PUBLIC = $data["public"];
				$CREATED = $data['created'];
				
					$IMG_P = "<a class=\"image\" href='admin.php?pilih=polling&amp;mod=yes&amp;aksi=type&amp;pid=$PID&amp;types=chart'><img src='images/ya.png' border='0' alt='edit menjadi Chart' /></a>";	
					
				if ($td == 0){
					$bgcolor = 'bgcolor="#efefef"';
				}else {
					$bgcolor = '';
				}	
				
	$admin.=  "    <tr $bgcolor id='pid_$PID'>
    <td width='4%' align='center'>$no</td>
    <td width='47%' >$PJUDUL</td>
    <td width='25%' align='center'>$CREATED</td>
    <td width='5%' align='center'><a class=\"image\" href='admin.php?pilih=polling&amp;mod=yes&amp;aksi=public&amp;pid=$PID&amp;public=$PUBLIC'><img src='images/$PUBLIC.png' border='0' alt='public' /></a></td>
        <td width='14%' align='center'><a class=\"image\" href=\"javascript:hapus('mod/polling/polling_delete.php?id=$PID','rowbody','pid_$PID');\"><img src='images/delete_button.gif' border='0' alt='delete Polling' /></a> <a class=\"image\" href='admin.php?pilih=polling&amp;mod=yes&amp;aksi=edit&amp;pid=$PID&amp;proses=1'><img src='images/edit.gif' border='0' alt='Edit Polling' /></a> </td>


</tr>";
				$no++;
			}
			mysql_free_result ($hasil);
			$admin.=  "</tbody></table>";
			
			
			
			
			$admin.=  "</div>";	
			
			
			$admin .= '<div id="responseajax"></div>';
			
		}

if ($aksi == 'add'){
 if(!isset($_POST['submit']))
		{
$admin.= "<form method='post' action='admin.php?pilih=polling&amp;mod=yes&amp;aksi=add'>
			<table class='formulir' border='0'>
				<tr>
					<td>Pejelasan Polling</td>
					<td>:</td>
					<td><input type='text' name='penjelasan' size='50' /></td>					
				</tr>
				<tr>
					<td valign='top'>Pilihan jawaban</td>
					<td valign='top'>:</td>
					<td>";
	if (empty ($jml_)){
		$jml_=5;
	}
	if($jml_ <= 30){				
 for ($j=1; $j<=$jml_; $j++){
	 
  $admin.= "<div>Pilihan $j <input type='text' name='pil$j' size='50' /></div>";	 
	
}	
}else {$admin.= "maaf max pilihan Polling 30 ";}  
$admin.= "<br />
<input type='hidden' name='penambahan' value='$jml_' /><input type='submit' name='submit' value='MASUKKAN POLLING BARU' />
</form>";
$admin.= "<form method='post' action='admin.php?pilih=polling&amp;mod=yes&amp;aksi=add'>add colom<input type='text' name='jml_' value='$jml_' size='3' />&nbsp;&nbsp;<input type='submit' name='jumlahkotak' value='add' /></form></td></tr></table>";

}
	else
		{
	if(empty ($penjelasan)){
	$admin.= "Maaf Judul Polling harus diisi";	
	}		
		else{
			$PJUDUL = $penjelasan;
			$PPILIHAN = "";
			$PJAWABAN = "";
			for($i=1;$i<=$penambahan;$i++)
			{
				$pil = "pil" . $i;
				if($$pil <> "")
				{
					$PPILIHAN .= $$pil . "#";
					$PJAWABAN .= "0" . "#";
				}
			}
			$PPILIHAN = substr_replace($PPILIHAN, "", -1, 1);
			$PJAWABAN = substr_replace($PJAWABAN, "", -1, 1);
			$sekarang = date("d-M-Y");
            $query = mysql_query("INSERT INTO polling (pjudul, ppilihan, pjawaban, created) VALUES ('$PJUDUL', '$PPILIHAN', '$PJAWABAN', '$sekarang')");
            
		$admin.='<div class="border"><table width="100%" class="bodyline"><tr><td align="left"><img src="images/info.gif" border="0" alt="info" /></td><td align="center"><font class="option">Polling baru sudah masuk ke dalam database<br /></font></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></div>';

}
}	
	
}

##################################################
## Edit Polling
##################################################

if ($_GET['aksi'] =='edit'){



if($_GET['proses'] == 1)
		{
			
			
			
            $hasil = mysql_query("SELECT * FROM polling WHERE pid='$pid'");
            
			$data = mysql_fetch_array($hasil);
			mysql_free_result ($hasil);
			$PID = $data["pid"];
			$PJUDUL = $data["pjudul"];
			$PPILIHAN = explode("#", $data["ppilihan"]);
			$PJAWABAN = explode("#", $data["pjawaban"]);
			$jml_pil = count($PPILIHAN);
		
$admin.= "<form method='post' action='admin.php?pilih=polling&amp;mod=yes&aksi=edit'>
Pejelasan Polling : <input type='text' name='penjelasan' size='50' value='$PJUDUL'>				
<table class='formulir' border='0'>
				";
if (isset($addq)){
$jml_pil = $jml_pil + $penambahan;	
}
					
for ($a=0; $a<$jml_pil; $a++){
	$b= $a+1;
if (empty ($PJAWABAN[$a])){
$PJAWABAN[$a] = 0;	
}		
$admin.= "<tr><td>Pilihan $b </td><td><input type='text' name='pil$b' value='$PPILIHAN[$a]' size='50'></td><td><input type='text' name='jawaban$b' size='2' value='$PJAWABAN[$a]'></td></tr>";
}


$admin .= '</table>';


$admin.= "<br>
<input type='hidden' name='proses' value='2'>
<input type='hidden' name='pid' value='$PID'>
<input type='hidden' name='penambahan' value='$jml_pil'>
<input type='submit' name='submit' value='EDIT POLLING'>
</form>";
$admin.= "<form method='post' action='admin.php?pilih=polling&amp;mod=yes&amp;aksi=edit&amp;pid=$pid&amp;proses=$proses'>
Penambahan Kolom : <input type='text' name='penambahan' size='5'>
<input type='submit' name='addq' value='Add'>";

$admin .= '<br><br>
Keterangan :<br>
Untuk Mengurangi Field / Kolom Silahkan Kosongkan Field "pilihan" Dari data Tersebut Diatas.
';


}

if($_GET['proses'] == 2)
		{
//*************************			
			
            $hasil1 = mysql_query("SELECT * FROM polling WHERE pid='$pid'");
            
			$data = mysql_fetch_array($hasil1);	
			mysql_free_result ($hasil1);
			$PPILIHAN_TEMP = explode("#", $data["ppilihan"]);
				$jml_pilihan = count($PPILIHAN_TEMP);	
			$PJUDUL = $penjelasan;
//***************************
$PPILIHAN = '';
$PJAWABAN = '';
			for($i=1; $i<=$penambahan; $i++)
			{
				$pil = "pil" . $i;
				$jwb = "jawaban" . $i;
				if($$pil <> "" and $$jwb <> "")
				{
					$PPILIHAN .= $$pil . "#";
					$PJAWABAN .= $$jwb . "#";
					
				}
			}
			$PPILIHAN = substr_replace($PPILIHAN, "", -1, 1);
			$PJAWABAN = substr_replace($PJAWABAN, "", -1, 1);
		    
            $hasil1 = mysql_query("UPDATE polling SET pjudul='$PJUDUL', ppilihan='$PPILIHAN', pjawaban='$PJAWABAN' WHERE pid='$pid'");
            
$admin.='<div class="border"><table width="100%" class="bodyline"><tr><td align="left"><img src="images/info.gif" border="0"></td><td align="center"><font class="option">Polling sudah di edit<br /></font></td><td align="right"><img src="images/info.gif" border="0"></td></tr></table></div>';
			
			
}	
}


echo $admin;
?>