<?
if (!defined('AURACMS_admin')) {
	Header("Location: ../index.php");
	exit;
}

if (!cek_login ()){
   exit;
}


$index_hal = 1;
	

function input_form ($alert, $nama, $value, $size=28, $type='text',$option=''){
if (!empty($value)) {$values = 'value="'.$value.'"';}else {$values='';}
$txt = "<input $alert onblur=\"$nama.style.color='#6A8FB1'; this.className='inputblur'\" onfocus=\"$nama.style.color='#FB6101'; this.className='inputfocus'\" type='$type' name='$nama' size='$size' $values $option>";
	
return $txt;	
}




$content = '<h4 class="bg">Shoutbox Manager</h4>
';




$_GET['action'] = !isset($_GET['action']) ? null : $_GET['action'];
switch ($_GET['action']){
default:
if (isset ($_POST['submit'])){
	
if (isset ($_POST['id'])){	
foreach ($_POST['id'] as $K=>$V){
	
	if (!empty ($V)) {
		$del = mysql_query ("DELETE FROM `shoutbox` WHERE `id`='$V'");
	}
	
}	
	
}	
	
	
}


$pager = mysql_query ("SELECT id FROM shoutbox");
$jumlah = mysql_num_rows($pager);

$limit = 20;
if (empty($_GET['offset']) and !isset ($_GET['offset'])) {
$offset = 0;
}else {
$offset = int_filter ($_GET['offset']);	
}
$a = new paging ($limit);

// Pembagian halaman dimulai
 if (!isset ($_GET['pg'],$_GET['stg'])){
	  $_GET['pg'] = 1;
	  $_GET['stg'] = 1;
  }

$paging =  $a-> getPaging($jumlah, $_GET['pg'], $_GET['stg']);
if (!empty ($paging)){
$content .= '<br><br>';
$content.= $paging;
$content .= ''; 
}
	




$content .= '<form method="post" action="#"><table style="width:100%">';

$perintah = "SELECT * FROM shoutbox ORDER BY id DESC LIMIT $offset,$limit";
$hasil = mysql_query( $perintah );
$no = 0 ;
while ($data = mysql_fetch_array($hasil)) {
$WAKTu = $data['waktu'];	
$NAMA = $data['nama'];
$EMAIL = $data['email'];
$ISI = $data['isi'];
$KET = $data['ket'];
$pecah = explode ('|', $KET);
$ID = $data['id'];

$ISI = preg_replace( '/(http|ftp)+(s)?:(\/\/)((\w|\.){2}+)(\/)?(\S+)?/i', '<a href="\0" target="_blank">Klik Here</a>', $ISI);	
$ISI = wordwrap($ISI, 20, ' ', 1);

if ($no % 2 == 0) {
$class = 'bgcolor="#efefef"';
}else {
$class = '';	
}	
$content .= "<tr $class><td colspan=\"2\"><input type='checkbox' name='id[]' value='$ID' style='border:0px' /> <a href=\"mailto:$EMAIL\" title=\"Ip: $pecah[0]
$pecah[1]\">" .substr($NAMA,0,15)."</a> : $ISI</td></tr>\n";
	$content .= "<tr $class><td colspan=\"2\"><span>$WAKTu</span></td></tr>\n";
	$content .= "<tr $class><td colspan=\"2\"></td></tr>\n";	
$no++;	
}
mysql_free_result ($hasil);



$content .= '</table>';
$content .= '<input type="submit" name="submit" value="hapus" class="submit" onclick="return confirm(\'Ingin Dihapus ?\')" />';

$content .= '</form>';


	break;
}
echo $content;
?>