<?

@ob_start('ob_gzhandler');
@header("Content-type: text/plain; charset=utf-8;");
@header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
@header("Pragma: no-cache");
include '../../../includes/session.php';
include '../../../includes/config.php';
include '../../../includes/fungsi.php';
include '../../../includes/mysql.php';
include '../../../includes/json.php';


if (!isset($_SESSION['mod_ajax'])){
exit;	
}

function callback($n){
$n = !isset($n) ? "0" : $n;
return $n;	
}

switch (@$_GET['action']){

	
case 'add':
if (isset($_GET['id'])){
$id = int_filter($_GET['id']);	

function BersihkanData($v){
return sensor(CleanText($v));	
}

$_POST = array_map ('BersihkanData',$_POST);
$_POST = array_map ('decodeURIComponent',$_POST);

$user = $_POST['user'];
$email = $_POST['email'];
$judul = $_POST['judul'];
$konten = $_POST['konten'];
$code = $_POST['codex'];



$error = '';
$open['error'] = false;
$open['errorpesan'] = '';
if (!eregi ("^[a-z0-9]+[._a-z0-9 ]+$",$user)) {$error .= "Error: Please enter your name!<br />";}
if (!is_valid_email($email)) {$error .= "Please use the standard format (admin@domain.com)<br />";}
if (empty($judul)) {$error .= "Please Enter Your Comment Title<br />";}
if (empty($konten)) {$error .= "Please Enter Your Comment<br />";}
if ($code != $_SESSION['Var_session'] or !isset($_SESSION['Var_session'])) {$error .= "Security Code Invalid <br />";}

 if (cek_posted('komentar_add.php')){
	 $error .= 'Anda Telah Memposting Data.. Tunggu Beberapa Saat Lagi'; 
	
    }	
	
if ($error != ''){
$open['error'] = true;
$open['errorpesan'] = $error;
}else {
	
	

$konten = substr($konten,0,500);	
$konten = wraptext($konten);
$judul = wraptext($judul);


$IP = getIP();
$time = date('Y-m-d H:i:s');
$hasil = mysql_query ("INSERT INTO komentar (`tgl`,`user`,`email`,`judul`,`konten`,`artikel`,`ip`) VALUES ('$time','$user','$email','$judul','$konten','$id','$IP')");	
if ($hasil){
//fungsi untuk proteksi yang telah memposting  
 posted('komentar_add.php');
}else {
$open['error'] = true;	
$open['errorpesan'] = mysql_error();
}
	
	
}	
	
	
$j = new JSON_obj();
echo $j->encode($open);	
	
}

break;	
	

default:
$id = int_filter($_GET['id']);
$query2 = mysql_query ("SELECT count(`komentar`.`id`) AS `total` FROM `komentar` WHERE `artikel` = '$id'");
$getdata2 = mysql_fetch_assoc($query2);
$jumlah = $getdata2['total'];
$limit = 5;
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
$ddl .= '<a onclick="komentar.indexs(\''.$v['link'].'\');" style="cursor:pointer" title="Page '.$v['title'].'">'.$v['title'].'</a> | ';
}
}
unset($pagging);
$pagging = substr($ddl,0,strlen($ddl)-3);
}else {
$pagging = null;	
}


$query = mysql_query ("SELECT * FROM `komentar` WHERE `artikel` = '$id' ORDER BY `id` DESC LIMIT $offset,$limit");
while ($data = mysql_fetch_assoc($query)){
$data = array_map('callback',$data);
$data['tgl'] = datetimes($data['tgl']);
$data['konten'] = nl2br($data['konten']);
$data['email'] = md5($data['email']);
$data['countdown'] = ($offset <= 0) ? $jumlah-- : (($jumlah--)-$offset);
$open['list'][] = $data;
}


if (!empty($pagging)){
$open['pagging'] = '<div class="border" style="text-align:center">'.$pagging.'</div>';
}else {
$open['pagging'] = '';	
}

$j = new JSON_obj();
echo $j->encode($open);
break;
}



?>