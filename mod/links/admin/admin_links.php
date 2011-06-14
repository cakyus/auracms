<?
if (!defined('AURACMS_admin')) {
    Header("Location: ../index.php");
    exit;
}
if (!cek_login ()){
    header ("location:index.php");
    exit;
}
$index_hal = 1;


function cek_baru_links ($kid, $expire=2073600, $kategori='kid', $tabel){
$waktu = time () - $expire;
$query1 = mysql_query("SELECT date FROM $tabel  WHERE date>='$waktu' AND $kategori='$kid' AND public=1");	
	
if (mysql_num_rows ($query1) > 0){
return "<b><sup style=\"color:red\">New</sup></b>";
}	
	
}
/*
function transcal($data){
return date ('Y-m-d H:i:s',$data);	
}
*/

$GLOBALS['tabel']['cat_link'] = 'mod_cat_link';
$GLOBALS['tabel']['link'] = 'mod_link';
$CONFIG['new_update'] = 1209600;

function incominglink(){
$query = mysql_query ("SELECT count(`id`) FROM `mod_link` WHERE `public` = 0");
$total = mysql_fetch_row($query);
$return = '';
if ($total[0] >= 1){
$return = '('.$total[0].')';	
}
return $return;
}

//| <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=broken'>View Broken Links</a>
$keterangan ="<h4 class=\"bg\">Links Manager</h4>";
$keterangan .="<div class=\"border\"><a href='admin.php?pilih=links&amp;mod=yes'>Home</a>  | <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=add'>Add Link</a> | <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=addkat'>Add Category</a>  | <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=incomming'>Incomming Links</a> ".incominglink()." | <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=cari'>Cari</a></div>";

$dl = '';

extract($_REQUEST);
if (!isset ($_GET['aksi'])) $aksi = ''; else $aksi = $_GET['aksi'];
if (!isset ($_GET['kid'])) $kid = null; else $kid = $_GET['kid'];
if (!isset ($_GET['id'])) $id = null; else $id = $_GET['id'];
if (!isset ($_GET['pg'])) $pg = null; else $pg = $_GET['pg'];
if (!isset ($_GET['stg'])) $stg = null; else $stg = $_GET['stg'];
if (!isset ($_GET['offset'])) $offset = null; else $offset = $_GET['offset'];







if ($aksi == ''){

$num = mysql_query("SELECT kid FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kid DESC");
$jumlah = mysql_num_rows ($num);
$limit = 10;
$a = new paging ($limit);



if (empty($offset)) {
$offset = 0;
}



$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kid DESC LIMIT $offset, $limit");

// Pembagian halaman dimulai

$halaman = intval($jumlah/$limit);
if ($jumlah%$limit) { 
$halaman++; 

}
$dl .='<table cellspacing="10" cellpadding="0" style="border:0"><tr>';
$no = 0 ;
while ($data = mysql_fetch_array($result)) {
$kategori = $data['kategori'];	
$keterangans = cleantext($data['keterangan']);
$kid = $data['kid'];	
$urutan =$no + 1;	


$getcount = mysql_query("SELECT count(kid) as score FROM ".$GLOBALS['tabel']['link']." WHERE kid='$kid'");
$hasil = mysql_fetch_array($getcount);

$dl .="<td style=\"width:50%\" valign=\"top\">- <a href='?pilih=links&amp;mod=yes&amp;aksi=lihat&amp;kid=$kid'><b>$kategori</b></a> (".$hasil['score'].") <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=editkat&amp;kid=$kid'>Edit</a>  <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=deletekat&amp;kid=$kid'>Delete</a><br />$keterangans</td>
                           <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <!-- pisah -->";
   if ($urutan % 2 == 0) {
$dl .= "</tr><tr>";	   
}
$no++;                            
 //  <TD>&nbsp;&nbsp;&nbsp;&nbsp;</TD>                          
                            
}
	
$dl .="</tr></table>";

// Pembagian halaman dimulai
 if (!isset ($pg,$stg)){
	  $pg = 1;
	  $stg = 1;
  }
 
$dl .= $a-> getPaging($jumlah, $pg, $stg);	
	
	
	
	
	
	
}






//$dl .=bukafile("js/alert.txt");


if ($aksi == 'mass'){

	
if (isset ($_POST['submit_edit_form'])){	
$total_edit = count (@$_POST['id']);
if ($total_edit > 0){
for ($i=0; $i<$total_edit; $i++){
	
	 $ids = int_filter ($_POST['id'][$i]);
	$djuduls = $_POST['djudul'][$i];
	$dketerangans = $_POST['dketerangan'][$i];
	 $durls = ($_POST['durl'][$i]);
	$kategori_dls = $_POST['kategori_dl'][$i];

	mysql_query("UPDATE ".$GLOBALS['tabel']['link']." SET 
					 `judul` = '$djuduls',
					 `keterangan` = '$dketerangans',
					 `url` = '$durls',
					 `kid` = '$kategori_dls'
					 WHERE `id` = '$ids'");


}
$query = mysql_query ("SELECT id FROM ".$GLOBALS['tabel']['link']." WHERE kid='".$_POST['kidnya']."'");
if (isset($_POST['mode_broken'])){
$ref = $_POST['http_referer'];
$ref = str_replace('&amp;','&',$ref);
header ("location:$ref");
exit;	
}
elseif (isset($_POST['mode_cari'])){
$ref = $_POST['http_referer'];
$ref = str_replace('&amp;','&',$ref);
header ("location:$ref");
exit;
}
elseif (mysql_num_rows ($query) > 0){
$ref = $_POST['http_referer'];
$ref = str_replace('&amp;','&',$ref);
header ("location:$ref");
exit;
}else {
header ("location:?pilih=links&mod=yes");
exit;
}	
	

//end total edit	
}

} // end POST submit_edit_form


if (isset ($_POST['submit_hapus_form'])){
	
$explode = explode (',', $_POST['id_array']);
if (is_array ($explode)){
	foreach ($explode as $k=>$v){
		$query = mysql_query ("DELETE  FROM ".$GLOBALS['tabel']['link']." WHERE id='$v'");
			}
	
$query = mysql_query ("SELECT id FROM ".$GLOBALS['tabel']['link']." WHERE kid='".$_POST['kidnya']."'");
if (isset($_POST['mode_broken'])){
$ref = $_POST['http_referer'];
$ref = str_replace('&amp;','&',$ref);
header ("location:$ref");
exit;		
}
elseif (isset($_POST['mode_cari'])){
$ref = $_POST['http_referer'];
header ("location:$ref");
exit;
}
elseif (mysql_num_rows ($query) > 0){
$ref = $_POST['http_referer'];
header ("location:$ref");	
exit;
}else {
header ("location:?pilih=links&mod=yes");
exit;
}	
}else {
$dl .= '<br /><br />ERROR ada data yang kurang<br /><br />';	
	
}	
	
	
	
}





	
	
}






if ($aksi == 'lihat'){
	
	
	
if (isset ($_POST['edit_form'])){
	
$hitung = count(@$_POST['links']);

$dl .= "<p>&nbsp;</p>Silahkan isi di bawah ini :<form method=\"post\" action=\"?pilih=links&amp;mod=yes&amp;aksi=mass\">";
for ($i=0; $i<$hitung; $i++){
$id = $_POST['links'][$i];	
$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE id='$id'");	
$row = mysql_fetch_array($result);
$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$IDs = $row['id'];	
$KID = $row['kid'];
$no = 0 ;	
$dl .= "Judul :<br /><input type='text' name='djudul[]' value='$JUDUL' size='54' /><br />Url :<br /><input type='text' name='durl[]' value='$URL' size='54' /><br />";


$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kategori");
$dl.= "Kategori<br /><select size='1' name='kategori_dl[]'>";
while ($data = mysql_fetch_array($result)) {
$KATEGORI = $data['kategori'];
$ID = $data['kid'];
if ($ID == $KID){
$dl.= "<option value='$ID' selected=\"selected\">$KATEGORI</option>";
}else {
$dl.= "<option value='$ID'>$KATEGORI</option>";	
}

}
$dl.= "</select>";

$dl .="<br /><br />
    KETERANGAN : <br/>
    <textarea rows=\"7\" name=\"dketerangan[]\" cols=\"40\">$KETERANGAN</textarea><br />
    <br />
    <input type=\"hidden\" name=\"id[]\" value=\"$IDs\" />
    ";
$dl .= '<input type="hidden" name="kidnya" value="'.int_filter($_GET['kid']).'" />';
}
$ref = $_SERVER['HTTP_REFERER'];
$ref = str_replace('&amp;','&',$ref);
$ref = str_replace('&','&amp;',$ref);
$dl .= "<br /><input type='hidden' name='http_referer' value=\"$ref\" /><input type=\"submit\" value=\"Edit\" name=\"submit_edit_form\" />
    </form>";	
	
	
}	
	
	
	
if (isset ($_POST['hapus_form'])){
	
$hitung = count ($_POST['links']);
if ($hitung > 0){
	$dl .= '<div class="error">Apakah Anda Yakin Ingin menghapus : </div>
	<form method="post" action="?pilih=links&amp;mod=yes&amp;aksi=mass">
	';
	for ($i=0; $i<$hitung; $i++) {
		$dl .= '- ' . $_POST['judul_array'][$i] . '<br />';
		
				}
	$implode = implode (',', $_POST['links']);
	$ref = $_SERVER['HTTP_REFERER'];
	$ref = str_replace('&amp;','&',$ref);
$ref = str_replace('&','&amp;',$ref);
	$dl .= '<br /><br /><input type="hidden" name="id_array" value="'.$implode.'" /><input type="hidden" name="http_referer" value="'.$ref.'" /><input type="hidden" name="kidnya" value="'.int_filter($_GET['kid']).'" /><input type="submit" name="submit_hapus_form" value="   YA   " />
	</form>';
	
	}	
	
	
	
}	
	
	
	
	
	
	
	
	
	
if (!isset ($_POST['edit_form']) && !isset ($_POST['hapus_form'])){	
$dl .= '<p>&nbsp;</p>
<form method="post" action="#" id="links" name="links">
<table><tr><td valign="top">';

$num = mysql_query("SELECT id FROM ".$GLOBALS['tabel']['link']."  WHERE kid='$kid' ORDER BY id DESC");
$jumlah = mysql_num_rows ($num);
$limit = 10;
$a = new paging ($limit);


if (empty($offset)) {
$offset = 0;
}

$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE kid='$kid' ORDER BY id DESC  LIMIT $offset, $limit");	
$no = 0 ;

if (!isset ($_GET['pg'],$_GET['stg'],$_GET['offset'])){
$Pg = 1;
$Stg = 1;
$Offset = 0;
}else {
$Pg = $_GET['pg'];
$Stg = $_GET['stg'];
$Offset = $_GET['offset'];	
}


$no = 0;
while ($row = mysql_fetch_array($result)) {

$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$ID = $row['id'];	
$KID = $row['kid'];
$dl .= '<input type="hidden" name="judul_array[]" value="'.$JUDUL.'" />';
$dl .= "<input type=\"checkbox\" name=\"links[]\" value=\"$ID\" style=\"border:none\" /> <b>$JUDUL</b> ($HIT) <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=edit&amp;id=$ID&amp;kid=$KID&amp;pg=$Pg&amp;stg=$Stg&amp;offset=$Offset'>edit</a> | <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=delete&amp;id=$ID&amp;kid=$KID' onclick=\"return confirmLink(this, 'DELETE FROM `$ID`  ')\">Hapus</a>
<br />$KETERANGAN<br /><b>URL :</b> <a href=\"$URL\" target=\"_BLANK\">$URL</a><br /><br />";
$no++;
}
$dl .="</td></tr></table>";

$dl .= '<a href="javascript:checkall(\'links\', \'links[]\')" title=\'Select All\'>Check All</a>&nbsp;&nbsp;&nbsp;';

$dl .= '

<input type="submit" name="edit_form" value="Edit" />&nbsp;&nbsp;&nbsp;<input type="submit" name="hapus_form" value="Hapus" />
</form>
<br />';
 if (!isset ($pg,$stg)){
	  $pg = 1;
	  $stg = 1;
  }
$dl .= $a-> getPaging($jumlah, $pg, $stg);	



}


}




#################################
## EDIT
################################

if ($aksi =='edit'){
	
if (isset($submit)){
	
$query1 = mysql_query("UPDATE ".$GLOBALS['tabel']['link']." SET judul='$djudul', keterangan='$dketerangan', url='$durl', kid='$kategori_dl' WHERE id='$id'");
	
$QuerySting = str_replace ('&aksi=edit', '&aksi=lihat', $_SERVER['QUERY_STRING']);
header ("location:?$QuerySting");
}
	
	
	
$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE id='$id'");	
$row = mysql_fetch_array($result);
$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$IDs = $row['id'];	
$KID = $row['kid'];
$no = 0 ;	
$dl .= "<p>Silahkan isi di bawah ini :<form method=\"post\" action=\"\">Judul :<br><input type=text name='djudul' value='$JUDUL' size=54><br>Url :<br><input type=text name='durl' value='$URL' size=54><br>";


$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kategori");
$dl.= "Kategori<br><select size='1' name='kategori_dl'>";
while ($data = mysql_fetch_array($result)) {
$KATEGORI = $data['kategori'];
$ID = $data['kid'];
if ($ID == $KID){
$dl.= "<option value='$ID' SELECTED>$KATEGORI</option>";
}else {
$dl.= "<option value='$ID'>$KATEGORI</option>";	
}

}
$dl.= "</select>";

$dl .="<br><br>
    KETERANGAN : <br/>
    <textarea rows=\"7\" name=\"dketerangan\" cols=\"40\">$KETERANGAN</textarea><br />
    <br>
    <input type=\"hidden\" name=\"id\" value=\"$IDs\" />
    <input type=\"submit\" value=\"Edit\" name=submit>
    </form>";
 		
}

##################################
## DELETE
###################################

if ($aksi =='delete'){
	
$query = mysql_query("DELETE FROM ".$GLOBALS['tabel']['link']." WHERE id='$id'");
	
if ($query){
//$dl .= "BERhasil di hapus";	
	header ("location:?pilih=links&amp;mod=yes&aksi=lihat&kid=".$_GET['kid']."");
}
	
}

#############################################
## ADD
#####################################

if ($aksi =='add'){
if (isset($submit)) {	
if (!empty($djudul) && !empty($durl) && !empty($dketerangan)){
$sekarang = time ();	
$hasil = mysql_query("INSERT INTO ".$GLOBALS['tabel']['link']." (judul, keterangan, url, date, public, kid) 
               VALUES ('$djudul','$dketerangan','$durl','$sekarang','1','$kategori_dl')");
               
              if ($hasil) {
	            $dl .= '<div class="sukses">Data Berhasil Dimasukkan</div>';  
              }	
	
	
} else {
	$dl .= '<div class="error">Silahkan Isi Form Dengan Benar</div>';
}	


}
$dl .= "Silahkan isi di bawah ini :<table class='formulir'><tr><td><form method=\"post\" action=\"?pilih=links&amp;mod=yes&amp;aksi=add\">Judul :<br /><input type='text' name='djudul' size='54' /><br />Url :<br /><input type='text' name='durl' size='54' /><br />";


$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kategori");
$dl.= "Kategori<br /><select size='1' name='kategori_dl'>";
while ($data = mysql_fetch_array($result)) {
$KATEGORI = $data['kategori'];
$ID = $data['kid'];
$dl.= "<option value='$ID'>$KATEGORI</option>";

}
$dl.= "</select>";

$dl .= "<br /><br />
    KETERANGAN : <br/>
    <textarea rows=\"7\" name=\"dketerangan\" cols=\"40\"></textarea><br />
    <br />
    <input type=\"submit\" value=\"Add\" name=\"submit\" />
    </form></td></tr></table>";		
	
}


#############################################
## ADD KATEGORI
#####################################

if ($aksi == 'addkat'){
if (isset ($submit)) {
if (!empty ($kat_download) && !empty ($ket_download)){
	
$hasil = mysql_query("INSERT INTO ".$GLOBALS['tabel']['cat_link']." (kategori, keterangan) 
               VALUES ('$kat_download','$ket_download')");		
	
$dl .="<div class='sukses'>Berhasil memasukkan kategori <b>$kat_download</b></div>";	
} else {
	$dl .="<div class='error'>Silahkan isi dengan benar</div>";	
}	

}
	
		
$dl .='<p>&nbsp;</p>';	
$dl .='<form method="post" action="#"><table border="0" cellpadding="2" cellspacing="1" style="border-collapse: collapse">
  <tr>
    <td width="27%">Kategori</td>
    <td width="2%">:</td>
    <td width="71%"><input type="text" name="kat_download" size="39" /></td>
  </tr>
  <tr>
    <td width="27%" valign="top">Keterangan (Max 255)</td>
    <td width="2%" valign="top">:</td>
    <td width="71%"><textarea rows="6" name="ket_download" cols="43"></textarea></td>
  </tr>
  <tr>
    <td width="27%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="71%">&nbsp;</td>
  </tr>
  <tr>
    <td width="27%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="71%"><input type="submit" value="Add" name="submit" /></td>
  </tr>
</table></form>';	
	
	
	
}


#############################################
## EDIT KATEGORI
#####################################

if ($aksi == 'editkat'){

if (isset ($submit) && !empty ($kat_download) && !empty ($ket_download)){
	
$hasil = mysql_query("UPDATE ".$GLOBALS['tabel']['cat_link']." SET kategori='$kat_download', keterangan='$ket_download' WHERE kid='$kid'");		
	
$dl .="<br>Berhasil Update kategori <b>$kat_download</b>";	
}	
	
	
$hasil = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." WHERE kid='$kid'");		

$cat = mysql_fetch_array ($hasil);			
$dl .='<p>&nbsp;</p>';	
$dl .='<form method="post" action=""><table border="0" cellpadding="2" cellspacing="1" style="border-collapse: collapse" bordercolor="#111111" width="100%">
  <tr>
    <td width="27%">Kategori</td>
    <td width="2%">:</td>
    <td width="71%"><input type="text" name="kat_download" size="39" value="'.$cat['kategori'].'"></td>
  </tr>
  <tr>
    <td width="27%" valign="top">Keterangan (Max 255)</td>
    <td width="2%" valign="top">:</td>
    <td width="71%"><textarea rows="6" name="ket_download" cols="43">'.$cat['keterangan'].'</textarea></td>
  </tr>
  <tr>
    <td width="27%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="71%">&nbsp;</td>
  </tr>
  <tr>
    <td width="27%">&nbsp;</td>
    <td width="2%">&nbsp;</td>
    <td width="71%"><input type="submit" value="Edit" name="submit"></td>
  </tr>
</table></form>';	
	
	
	
}

#############################################
## DELEETE KATEGORI
#####################################

if ($aksi == 'deletekat'){

if (isset ($submit)	&& $confirm == 1){
	
	
$query = mysql_query("DELETE FROM ".$GLOBALS['tabel']['cat_link']." WHERE kid='$KID'");
	
if ($query){
//$dl .= "BERhasil di hapus";
	
$query = mysql_query("DELETE FROM ".$GLOBALS['tabel']['link']." WHERE kid='$KID'");
		
	header ("location:?pilih=links&mod=yes");
	exit;
}	
	
	
	
	
}
	
	
	
	
$dl .="<div class=\"error\">Apakah anda ingin menghapus kategori tersebut INGAT !!! semua data yang ada di dalam kategori tersebut akan hilang</div>";
$dl .='<form method="post" action="#">
<input type="hidden" name="confirm" value="1" />
<input type="hidden" name="KID" value="'.$kid.'" />
<input type="submit" value="Ya" name="submit" />
</form>';
}


if ($aksi == 'broken'){
if (!isset ($mode)) $mode = '';
	
if ($mode == 'ya'){

mysql_query("UPDATE ".$GLOBALS['tabel']['link']." SET broken=0 WHERE id='$id'");	
	
}


if (isset ($_POST['edit_form'])){
	
$hitung = count(@$_POST['links']);

$dl .= "<p>&nbsp;</p>Silahkan isi di bawah ini :<form method=\"post\" action=\"?pilih=links&amp;mod=yes&aksi=mass\">";
for ($i=0; $i<$hitung; $i++){
$id = $_POST['links'][$i];	
$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE id='$id'");	
$row = mysql_fetch_array($result);
$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$IDs = $row['id'];	
$KID = $row['kid'];
$no = 0 ;	
$dl .= "Judul :<br><input type=text name='djudul[]' value='$JUDUL' size=54><br>Url :<br><input type=text name='durl[]' value='$URL' size=54><br>";


$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kategori");
$dl.= "Kategori<br><select size='1' name='kategori_dl[]'>";
while ($data = mysql_fetch_array($result)) {
$KATEGORI = $data['kategori'];
$ID = $data['kid'];
if ($ID == $KID){
$dl.= "<option value='$ID' SELECTED>$KATEGORI</option>";
}else {
$dl.= "<option value='$ID'>$KATEGORI</option>";	
}

}
$dl.= "</select>";

$dl .="<br><br>
    KETERANGAN : <br/>
    <textarea rows=\"7\" name=\"dketerangan[]\" cols=\"40\">$KETERANGAN</textarea><br />
    <br>
    <input type=\"hidden\" name=\"id[]\" value=\"$IDs\" />
    ";
//$dl .= '<input type=hidden name="kidnya" value="'.int_filter($_GET['kid']).'">';
}
$ref = $_SERVER['HTTP_REFERER'];
$dl .= "<br><input type='hidden' name='http_referer' value=\"$ref\"><input type='hidden' name='mode_broken' value='1'><input type=\"submit\" value=\"Edit\" name=submit_edit_form>
    </form>";	
	
	
}	
	
	
	
if (isset ($_POST['hapus_form'])){
	
$hitung = count ($_POST['links']);
if ($hitung > 0){
	$dl .= '<br><br>Apakah Anda Yakin Ingin menghapus : <br><br>
	<form method="POST" action="?pilih=links&amp;mod=yes&aksi=mass">
	';
	for ($i=0; $i<$hitung; $i++) {
		$dl .= '<li>' . $_POST['judul_array'][$i];
		
				}
	$implode = implode (',', $_POST['links']);
	$ref = $_SERVER['HTTP_REFERER'];
	$dl .= '<br><br><input type="hidden" name="id_array" value="'.$implode.'"><input type="hidden" name="http_referer" value="'.$ref.'"><input type="hidden" name="mode_broken" value="1"><input type="submit" name="submit_hapus_form" value="   YA   ">
	</form>';
	
	}	
	
	
	
}	


if (isset ($_POST['ignore_form'])){
	
$hitung = count ($_POST['links']);
if ($hitung > 0){

	for ($i=0; $i<$hitung; $i++) {
		//$dl .= '<li>' . $_POST['judul_array'][$i];
		$id = $_POST['links'][$i];
		mysql_query("UPDATE ".$GLOBALS['tabel']['link']." SET broken=0 WHERE id='$id'");
				}

	
	}	
	
	
	
}	





	
	
if (!isset ($_POST['edit_form']) && !isset ($_POST['hapus_form'])){	
	
$dl .='<p>&nbsp;</p>';

$num = mysql_query("SELECT id FROM ".$GLOBALS['tabel']['link']."  WHERE broken>0 ORDER BY broken DESC");
$jumlah = mysql_num_rows ($num);
$limit = 10;
$a = new paging ($limit);


if (empty($offset)) {
$offset = 0;
}

$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE broken>0 ORDER BY broken DESC  LIMIT $offset, $limit");	
$no = 0 ;
$dl .= '<form method="POST" action="" name="links" id="links">';
while ($row = mysql_fetch_array($result)) {

$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$ID = $row['id'];	
$BROKEN = $row['broken'];
$dl .= '<input type="hidden" name="judul_array[]" value="'.$JUDUL.'">';
$dl .= "<input type=\"checkbox\" name=\"links[]\" value=\"$ID\" style=\"border:none\"> <b>$JUDUL</b> ($HIT) <a href='admin.php?pilih=links&amp;mod=yes&aksi=edit&id=$ID'>edit</a> | <a href='admin.php?pilih=links&amp;mod=yes&aksi=delete&id=$ID'>Hapus</a> | <a href='?pilih=links&amp;mod=yes&aksi=broken&mode=ya&id=$ID'>Ignore</a>
<br>$KETERANGAN <br>
<b>Url :</b> <a href='$URL' target='_blank'>$URL</a> <b>Broken :</b> $BROKEN
<br><br>";

}
$dl .= '<a href="javascript:checkall(\'links\', \'links[]\')" title=\'Select All\'>Check All</a>&nbsp;&nbsp;&nbsp;';

$dl .= '

<input type="submit" name="edit_form" value="Edit">&nbsp;&nbsp;&nbsp;<input type="submit" name="hapus_form" value="Hapus">&nbsp;&nbsp;&nbsp;<input type="submit" name="ignore_form" value="Ignore">
</form>
<br>';
 if (!isset ($pg,$stg)){
	  $pg = 1;
	  $stg = 1;
  }
$dl .= $a-> getPaging($jumlah, $pg, $stg);	

	
	
}	
	
}

if ($aksi == 'incomming'){
if (!isset ($mode)) $mode = '';
	if ($mode == 'ya'){
$sekarang = time ();
mysql_query("UPDATE ".$GLOBALS['tabel']['link']." SET public=1, `date`= '$sekarang' WHERE id='$id'");	
	
}	
	

if (isset ($_POST['edit_form'])){
	
$hitung = count(@$_POST['links']);

$dl .= "<p>&nbsp;</p>Silahkan isi di bawah ini :<form method=\"post\" action=\"?pilih=links&amp;mod=yes&amp;aksi=mass\">";
for ($i=0; $i<$hitung; $i++){
$id = $_POST['links'][$i];	
$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE id='$id'");	
$row = mysql_fetch_array($result);
$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$IDs = $row['id'];	
$KID = $row['kid'];
$no = 0 ;	
$dl .= "Judul :<br /><input type='text' name='djudul[]' value='$JUDUL' size='54' /><br />Url :<br /><input type='text' name='durl[]' value='$URL' size='54' /><br />";


$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kategori");
$dl.= "Kategori<br /><select size='1' name='kategori_dl[]'>";
while ($data = mysql_fetch_array($result)) {
$KATEGORI = $data['kategori'];
$ID = $data['kid'];
if ($ID == $KID){
$dl.= "<option value='$ID' selected='selected'>$KATEGORI</option>";
}else {
$dl.= "<option value='$ID'>$KATEGORI</option>";	
}

}
$dl.= "</select>";

$dl .="<br /><br />
    KETERANGAN : <br />
    <textarea rows=\"7\" name=\"dketerangan[]\" cols=\"40\">$KETERANGAN</textarea><br />
    <br />
    <input type=\"hidden\" name=\"id[]\" value=\"$IDs\" />
    ";
//$dl .= '<input type=hidden name="kidnya" value="'.int_filter($_GET['kid']).'">';
}
$ref = $_SERVER['HTTP_REFERER'];
$ref = str_replace('&','&amp;',$ref);
$dl .= "<br /><input type='hidden' name='http_referer' value=\"$ref\" /><input type='hidden' name='mode_broken' value='1' /><input type=\"submit\" value=\"Edit\" name=\"submit_edit_form\" />
    </form>";	
	
	
}	
	
	
	
if (isset ($_POST['hapus_form'])){
	
$hitung = count ($_POST['links']);
if ($hitung > 0){
	$dl .= '<div class="error">Apakah Anda Yakin Ingin menghapus : </div>
	<form method="post" action="?pilih=links&amp;mod=yes&amp;aksi=mass">
	';
	for ($i=0; $i<$hitung; $i++) {
		$dl .= '- ' . $_POST['judul_array'][$i] . '<br />';
		
				}
	$implode = implode (',', $_POST['links']);
	$ref = $_SERVER['HTTP_REFERER'];
	$ref = str_replace('&','&amp;',$ref);
	$dl .= '<br /><br /><input type="hidden" name="id_array" value="'.$implode.'" /><input type="hidden" name="http_referer" value="'.$ref.'" /><input type="hidden" name="mode_broken" value="1" /><input type="submit" name="submit_hapus_form" value="   YA   " />
	</form>';
	
	}	
	
	
	
}	


if (isset ($_POST['accept_form'])){
	
$hitung = count ($_POST['links']);
if ($hitung > 0){
$sekarang = time () + $GLOBALS['timeplus'];
	for ($i=0; $i<$hitung; $i++) {
		//$dl .= '<li>' . $_POST['judul_array'][$i];
		$id = $_POST['links'][$i];
		
		mysql_query("UPDATE ".$GLOBALS['tabel']['link']." SET public=1, `date`= '$sekarang' WHERE id='$id'");	

				}

	
	}	
	
	
	
}




if (!isset ($_POST['edit_form']) && !isset ($_POST['hapus_form'])){	
	
$dl .='<p>&nbsp;</p>';

$num = mysql_query("SELECT id FROM ".$GLOBALS['tabel']['link']."  WHERE public=0 ORDER BY id DESC");
$jumlah = mysql_num_rows ($num);
$limit = 10;
$a = new paging ($limit);


if (empty($offset)) {
$offset = 0;
}

$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE public=0 ORDER BY id DESC  LIMIT $offset, $limit");	
$no = 0 ;

$dl .= '<form method="post" action="#" name="links" id="links">';

while ($row = mysql_fetch_array($result)) {

$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$ID = $row['id'];	

$dl .= '<input type="hidden" name="judul_array[]" value="'.$JUDUL.'" />';
$dl .= "<input type=\"checkbox\" name=\"links[]\" value=\"$ID\" style=\"border:none\" /> <b>$JUDUL</b> ($HIT) <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=edit&amp;id=$ID'>edit</a> | <a href='admin.php?pilih=links&amp;mod=yes&amp;aksi=delete&amp;id=$ID'>Hapus</a> | <a href='?pilih=links&amp;mod=yes&amp;aksi=incomming&amp;mode=ya&amp;id=$ID'>Accept</a>
<br />$KETERANGAN <br />
<b>Url :</b> <a href='$URL' target='_blank'>$URL</a>
<br /><br />";

}

if ($jumlah > 0) {
$dl .= '<a href="javascript:checkall(\'links\', \'links[]\')" title=\'Select All\'>Check All</a>&nbsp;&nbsp;&nbsp;';

$dl .= '
<input type="submit" name="edit_form" value="Edit" />&nbsp;&nbsp;&nbsp;<input type="submit" name="hapus_form" value="Hapus" />&nbsp;&nbsp;&nbsp;<input type="submit" name="accept_form" value="Accept" />';
}
$dl .= '</form>
<br />';
 if (!isset ($pg,$stg)){
	  $pg = 1;
	  $stg = 1;
  }
$dl .= $a-> getPaging($jumlah, $pg, $stg);	

	
}
	
	
}



if ($aksi == 'cari'){
include 'includes/search.lib.php';




if (!isset ($_GET['search']))$search = ''; else $search = $_GET['search'];
$dl .="

Silahkan masukkan kata kunci <br />
<table><tr><td><form method='get' action=''>
Search&nbsp;   <input class='input' type='text' name='search' size='20' value=\"" . 
stripslashes(htmlspecialchars (urldecode ($search)))
."\" />&nbsp;
  <select size='1' name='type'>
";
if (!isset ($type) or $type == 1){
$dl .="
<option selected='selected' value='1'>Boolean Mode</option>
<option value='2'>Seluruh kata</option>
<option value='3'>kata yg tepat</option>	
";	
}else if ($type == 2){
$dl .="
<option value='1'>Boolean Mode</option>
<option selected='selected' value='2'>Seluruh kata</option>
<option value='3'>kata yg tepat</option>	
";	
}else {
$dl .="
<option value='1'>Boolean Mode</option>
<option value='2'>Seluruh kata</option>
<option selected='selected' value='3'>kata yg tepat</option>	
";		
	
}
  

$dl .="

  

  </select>
  &nbsp;&nbsp;&nbsp;<input type='hidden' name='pilih' value='links' /><input type='hidden' name='mod' value='yes' /><input type='hidden' name='aksi' value='cari' />
  <input type='submit' value='Cari' />
</form></td></tr></table>
";	

if (isset ($_POST['edit_form'])){
	
$hitung = count(@$_POST['links']);

$dl .= "<p>&nbsp;</p>Silahkan isi di bawah ini :<form method=\"post\" action=\"?pilih=links&amp;mod=yes&amp;aksi=mass\">";
for ($i=0; $i<$hitung; $i++){
$id = $_POST['links'][$i];	
$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE id='$id'");	
$row = mysql_fetch_array($result);
$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$HIT = $row['hit'];
$IDs = $row['id'];	
$KID = $row['kid'];
$no = 0 ;	
$dl .= "Judul :<br /><input type='text' name='djudul[]' value='$JUDUL' size='54' /><br />Url :<br /><input type='text' name='durl[]' value='$URL' size='54' /><br />";


$result = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['cat_link']." ORDER BY kategori");
$dl.= "Kategori<br /><select size='1' name='kategori_dl[]'>";
while ($data = mysql_fetch_array($result)) {
$KATEGORI = $data['kategori'];
$ID = $data['kid'];
if ($ID == $KID){
$dl.= "<option value='$ID' selected='selected'>$KATEGORI</option>";
}else {
$dl.= "<option value='$ID'>$KATEGORI</option>";	
}

}
$dl.= "</select>";

$dl .="<br /><br />
    KETERANGAN : <br />
    <textarea rows=\"7\" name=\"dketerangan[]\" cols=\"40\">$KETERANGAN</textarea><br />
    <br />
    <input type=\"hidden\" name=\"id[]\" value=\"$IDs\" />
    ";
//$dl .= '<input type=hidden name="kidnya" value="'.int_filter($_GET['kid']).'">';
}
$ref = $_SERVER['HTTP_REFERER'];
$ref = str_replace('&','&amp;',$ref);
$dl .= "<br /><input type='hidden' name='http_referer' value=\"$ref\" /><input type='hidden' name='mode_cari' value='1' /><input type=\"submit\" value=\"Edit\" name=\"submit_edit_form\" />
    </form>";	
	
	
}	
	
	
	
if (isset ($_POST['hapus_form'])){
	
$hitung = count ($_POST['links']);
if ($hitung > 0){
	$dl .= '<br /><br />Apakah Anda Yakin Ingin menghapus : <br /><br />
	<form method="post" action="?pilih=links&amp;mod=yes&amp;aksi=mass">
	';
	for ($i=0; $i<$hitung; $i++) {
		$dl .= '<li>' . $_POST['judul_array'][$i];
		
				}
	$implode = implode (',', $_POST['links']);
	$ref = $_SERVER['HTTP_REFERER'];
	$dl .= '<br /><br /><input type="hidden" name="id_array" value="'.$implode.'"><input type="hidden" name="mode_cari" value="1"><input type="hidden" name="http_referer" value="'.$ref.'" /><input type="submit" name="submit_hapus_form" value="   YA   " />
	</form>';
	
	}	
	
	
	
}	









if (!isset ($_POST['edit_form']) && !isset ($_POST['hapus_form'])){


$sss = str_word_count($search);
if (isset ($search) AND !empty ($search) AND $sss !==0){
$synonym_list='';
$stopword_list[] = '&';
$stopword_list[] = ',';
$stopword_list[] = '@';
$stopword_list[] = '#';
$stopword_list[] = '"';
$stopword_list[] = '\'';
$stopword_list[] = '?';
$stopword_list[] = '!';
$search = clean_words('search', $search, $stopword_list, $synonym_list);	
	
$search = trim($search);

$limit = 10;


if ($type == 1){


if (empty($offset) and !isset ($offset)) {
$offset = 0;

}	
$num = mysql_query("SELECT id,
		 			MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE) AS score 
	  				FROM ".$GLOBALS['tabel']['link']." where MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE) AND public=1 
	  				");
	  
	  
$jumlah = mysql_num_rows ($num);
mysql_free_result ($num);
$A = new paging ($limit);
		
$hasil4 = mysql_query  ("SELECT judul,
								keterangan,
								url,
								hit,
								date,
								broken,
								public,
								kid,
								id,
		 		MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE) AS score 
	  			FROM ".$GLOBALS['tabel']['link']." where MATCH(judul,keterangan,url) AGAINST ('$search'  IN BOOLEAN MODE)  AND public=1 
	  			 ORDER BY score DESC
	 			LIMIT $offset, $limit");
	

	
//menutup database	
	
}
//pencarian menggunakan seluruh kata
elseif ($type == 2){
	
 $bool ='AND';
 $keywords_array= str_word_count($search, 1);
   $retornar = " ( ";
   foreach ($keywords_array as $keyw) {
	   if (strlen ($keyw) > 1){
       if ($retornar!=" ( ") {$retornar .= " ".$bool." ";}
       $retornar .= " (judul LIKE '%".$keyw."%') OR (keterangan LIKE '%".$keyw."%')  OR (url LIKE '%".$keyw."%')";
 	}else {
	 	if ($retornar!=" ( ") {$retornar .= " ".$bool." ";}
       $retornar .= " (judul LIKE '%cvbcvbcvbcvb%') OR (keterangan LIKE '%cvbcvbcvb%') ";
 	}
   }
   $retornar .= " ) ";		
	
$was = mysql_query ("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE $retornar and public=1");

$jumlah = mysql_num_rows ($was);
mysql_free_result ($was);

$A = new paging($limit);
if (empty($offset) and !isset ($offset)) {
$offset = 0;

}		
$hasil4 = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE $retornar  and public=1 LIMIT $offset, $limit");		
		
	
	
}
else {
	
$retornar = " (judul LIKE '%".$search."%') OR (keterangan LIKE '%".$search."%')  OR (url LIKE '%".$search."%')";	
	
	
$was = mysql_query ("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE $retornar and public=1");

$jumlah = mysql_num_rows ($was);
mysql_free_result ($was);

$A = new paging ($limit);
if (empty($offset) and !isset ($offset)) {
$offset = 0;

}		
$hasil4 = mysql_query("SELECT * FROM ".$GLOBALS['tabel']['link']." WHERE $retornar and public=1 LIMIT $offset, $limit");		
	
}	
	




$totsearch = mysql_num_rows($hasil4);
if ($totsearch > 0) {
$dl.= "Ditemukan <b>".$jumlah ."</b> Links dengan kata kunci <b>".htmlspecialchars($search)."</b>";	
}else {
	$dl.= "Tidak Ditemukan links dengan kata kunci <b>".htmlspecialchars($search)."</b>";
	

}

$dl .= '<br />';

  $j= 0;
  
$sc = stripslashes(htmlspecialchars (urldecode ($_GET['search'])));
$type = int_filter ($_GET['type']);




$dl .= '<form method="post" action="#" name="links" id="links">';

while ($row = mysql_fetch_array ($hasil4)){
///// fungsi hightlight
//////////////////////////////////////////////////////////////////////////////////////////////////////////	
$highlight = $search;

if (isset($search)){
	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars(urldecode($search))));
$highlight_match = '';
	for($i = 0; $i < sizeof($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', phpbb_preg_quote($words[$i], '#'));
		}
	}
	unset($words);
	
}

$JUDUL = $row['judul'];
$KETERANGAN = cleantext($row['keterangan']);
$URL = $row['url'];
$ID = $row['id'];	
$DATE = transCAL ($row['date'],'id', true);
$HIT = $row['hit'];
$kid = $row['kid'];

$JUDUL = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $highlight_match . ")#i', '<span class=\'pencarian\'>\\\\1</span>', '\\0')", '>' . $JUDUL . '<'), 1, -1));
$KETERANGAN = str_replace('\"', '"', substr(preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "preg_replace('#(" . $highlight_match . ")#i', '<span class=\'pencarian\'>\\\\1</span>', '\\0')", '>' . $KETERANGAN . '<'), 1, -1));

$dl .= '<input type="hidden" name="judul_array[]" value="'.$JUDUL.'" />';
$dl .= "<input type=\"checkbox\" name=\"links[]\" value=\"$ID\" style=\"border:none\" />&nbsp;<b><a href='$URL' title='$URL' target='_blank'>$JUDUL</a></b>" . cek_baru_links ($ID,$CONFIG['new_update'], 'id', $GLOBALS['tabel']['link']) . "
<br />$KETERANGAN<br />
<b>Added on:</b> $DATE <b>View:</b> $HIT  <br /><br />
";





}
mysql_free_result ($hasil4);

$dl .= '<br /><a href="javascript:checkall(\'links\', \'links[]\')" title=\'Select All\'>Check All</a>&nbsp;&nbsp;&nbsp;';

$dl .= '

<input type="submit" name="edit_form" value="Edit" />&nbsp;&nbsp;&nbsp;<input type="submit" name="hapus_form" value="Hapus" />';
$dl .= '</form>';
$dl.="<br /><br />";
 if (!isset ($pg,$stg)){
	  $pg = 1;
	  $stg = 1;
  }
$dl.= $A-> getPaging($jumlah, $pg, $stg);


}


}//end !isset edit_form


}



$admin = $keterangan;
$admin.= $dl;
echo <<<js
<script type="text/javascript">
<!-- //hide
function increment(fieldName)
{
	document.getElementById(fieldName).value++; 
}
all_checked = true;
function checkall(formName, boxName) {
	for(i = 0; i < document.getElementById(formName).elements.length; i++)
	{
		var formElement = document.getElementById(formName).elements[i];
		if(formElement.type == 'checkbox' && formElement.name == boxName && formElement.disabled == false)
		{
			formElement.checked = all_checked;
		}
	}	
all_checked = all_checked ? false : true;
}
// don't hide -->
</script>
js;
echo $admin;
?>