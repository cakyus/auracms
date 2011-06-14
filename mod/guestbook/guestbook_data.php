<?

if (defined('AURACMS_CONTENT')) {
exit;	
}
@ob_start('ob_gzhandler');
@header("Content-type: text/plain; charset=utf-8;");
@header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
@header("Pragma: no-cache");

include '../../includes/session.php';
include '../../includes/config.php';
include '../../includes/fungsi.php';
include '../../includes/mysql.php';
include '../../includes/json.php';

if (!isset($_SESSION['mod_ajax'])){
exit;	
}


switch (@$_GET['action']){
case 'setting':
$getconfig = mysql_query("SELECT * FROM `bukutamu_config` WHERE `id` = '1'");
$dataconfig = mysql_fetch_assoc($getconfig);
$gbconfig = unserialize($dataconfig['config']);
$j = new JSON_obj();
echo $j->encode($gbconfig);	
break;	
case 'add':
function BersihkanData($v){
return sensor(cleantext($v));	
}

if (count($_POST) <= 0){
exit;	
}

$_POST = array_map ('BersihkanData',$_POST);
//$_POST = array_map ('decodeURIComponent',$_POST);

$error = '';

    $homepage = $_POST['homepage'];
    $alamat = $_POST['alamat'];
    $email = $_POST['email'];
    $nama = $_POST['nama'];
    $komentar = $_POST['message'];
    $gfx_check = $_POST['gfx_check'];
    
    
    //is_valid_email
    if (validate_url($homepage)){
	$homepage =  'http://' . eregi_replace ('http://', '' , $homepage);
	}
	else {
	$homepage = '';
	}
	
	


	
	
	
if (empty($nama) or !eregi ("^[a-z0-9]+[._a-z0-9 ]+$",$nama)) {$error .= "Error: Please enter your name!<br />";}
if (!is_valid_email($email)) {$error .= "Please use the standard format (admin@domain.com)<br />";}
if (empty($alamat)) {$error .= "Error: Please enter your Address!<br />";}
if (empty($komentar)) {$error .= "Error: Please enter a message!<br />";}
if ($gfx_check != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) {$error .= "Security Code Invalid <br />";}



if (cek_posted('guestbook')){
	$error .= 'Anda Telah Memposting, Tunggu beberapa Saat';
}

if ($error != ''){
$open['pesanError'] = $error;	
$open['error'] = true;
	
}else {
	
	$getconfig = mysql_query("SELECT * FROM `bukutamu_config` WHERE `id` = '1'");
	$dataconfig = mysql_fetch_assoc($getconfig);
	$gbconfig = unserialize($dataconfig['config']);
	$maxChar = empty($gbconfig['char']) ? 500 : $gbconfig['char'];
	$komentar = substr ($komentar,0,$maxChar);
 $sekarang = date("d-M-Y");
    $perintah1="INSERT INTO bukutamu (sekarang, nama, email, homepage, alamat, komentar) VALUES ('$sekarang', '$nama', '$email', '$homepage', '$alamat', '$komentar')";
    $hasil = mysql_query($perintah1);
    if ($hasil){	
$open['error'] = false;	


Posted('guestbook');

}else {
$open['error'] = true;	
$open['pesanError'] = "gagal Memasukkan Data Kedalam Database";
}

}


$j = new JSON_obj();
echo $j->encode($open);	

break;	
	
default:


$query1 = mysql_query ("SELECT count(`id`) AS `total` FROM `bukutamu`");
$getdata1 = mysql_fetch_assoc($query1);
$jumlah = $getdata1['total'];
$getconfig = mysql_query("SELECT * FROM `bukutamu_config` WHERE `id` = '1'");
$dataconfig = mysql_fetch_assoc($getconfig);
$gbconfig = unserialize($dataconfig['config']);
$limit = empty($gbconfig['limit']) ? 5 : $gbconfig['limit'];
$pembagian = new paging ($limit);
if (empty($_GET['offset']) and !isset ($_GET['offset'])) {
$offset = 0;
}else {
$offset = int_filter($_GET['offset']);	
}

if (empty($_GET['pg']) and !isset ($_GET['pg'])) {
$pg = 1;
}else {
$pg = int_filter($_GET['pg']);	
}
if (empty($_GET['stg']) and !isset ($_GET['stg'])) {
$stg = 1;
}else {
$stg = int_filter($_GET['stg']);		
}


$pagging = $pembagian-> getPagingajax($jumlah, $pg, $stg);
if (is_array($pagging)){
$ddl = '';
foreach ($pagging as $k=>$v){
if ($v['link'] == ""){
$ddl .= $v['title'].' | ';
}else {
$ddl .= '<a onclick="bukutamu(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}



$query = mysql_query ("SELECT * FROM `bukutamu` ORDER BY `id` DESC LIMIT ".$offset.",$limit");
$nowtotalalbum = mysql_num_rows($query);
while ($data = mysql_fetch_array ($query)){
	
	
$data_buku = explode(" ",$data['komentar']);
$data['komentar']="";
for ($i=0; $i<count($data_buku); $i++){

        if (strlen($data_buku[$i]) >= 20) {
            $data_buku[$i] = wordwrap($data_buku[$i], 30, " ", TRUE);
        }
        $data['komentar'] .= $data_buku[$i]." ";
}	
	
	
	
	
$open['bukutamuList'][] = array ('id'=>$data['id'],'sekarang'=>$data['sekarang'],'nama'=>$data['nama'],'email'=>$data['email'],'homepage'=>$data['homepage'],'alamat'=>$data['alamat'],'komentar'=>sensor($data['komentar']),'jawab'=>$data['jawab']);	
}

if (!empty($pagging)){
$open['paging'] = $pagging;	
}else {
$open['paging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);	



break;
}



  
?>